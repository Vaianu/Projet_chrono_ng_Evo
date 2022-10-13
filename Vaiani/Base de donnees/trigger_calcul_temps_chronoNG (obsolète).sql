DELIMITER $$
DROP TRIGGER IF EXISTS trg_chrono;
$$
CREATE TRIGGER trg_chrono 
BEFORE UPDATE 
ON chrono FOR EACH ROW
BEGIN
--
	DECLARE t_heure 					varchar(2);
	DECLARE t_min 						varchar(2);
	DECLARE t_sec 						varchar(2);
	--
	DECLARE t_heureDepartEnSec			varchar(10);
	DECLARE t_heureInterEnSec			varchar(10);
	DECLARE t_heureArriveEnSec			varchar(10);
	DECLARE t_totalTempsEnSec			varchar(10);
	DECLARE t_tempInter					varchar(10);
	DECLARE t_tempFinal					varchar(10);
	--
	DECLARE t_tempsInterChampion		varchar(10);
	DECLARE t_tempsInterChampionEnSec	varchar(10);
	DECLARE t_IdREURChampion			int;
	DECLARE t_tempsInterREURActuEnSec	varchar(10);
	DECLARE t_tempDiff					varchar(10);
	--
	-- to_char(NEW.t_inter, 'HH12:MI:SS');
	IF OLD.t_inter<>NEW.t_inter THEN
		--
		-- Calcul du temps mis par le coureur entre la station depart et station intermediaire
		--
		-- Conversion de l'heure de passage du coureur à la station 1 (depart) en secondes
		SET t_heureDepartEnSec := HOUR(NEW.t_depart) * 3600;
		SET t_heureDepartEnSec := t_heureDepartEnSec + MINUTE(NEW.t_depart) * 60;
		SET t_heureDepartEnSec := t_heureDepartEnSec + SECOND(NEW.t_depart);
		-- Conversion de l'heure de passage du coureur à la station 2 (inter) en secondes
		SET t_heureInterEnSec := HOUR(NEW.t_inter) * 3600;
		SET t_heureInterEnSec := t_heureInterEnSec + MINUTE(NEW.t_inter) * 60;
		SET t_heureInterEnSec := t_heureInterEnSec + SECOND(NEW.t_inter);
		-- Calcul du temps mis par le coureur entre la station 1 (depart) et la 2 (inter)
		SET t_totalTempsEnSec := t_heureInterEnSec - t_heureDepartEnSec;
		SET t_min := t_totalTempsEnSec / 60;
		SET t_sec := MOD(t_totalTempsEnSec, 60);
		SET t_heure := t_min / 60;
		SET t_min := MOD(t_min, 60);
		SET	t_tempInter := CONCAT(t_heure, ':', t_min, ':', t_sec);
		-- Maj du champ tempsInter du coureur dans la table chrono en bdd
		SET NEW.tempsInter := t_tempInter;
		--
		-- Calcul de l'ecart du coureur avec le premier à mi-parcours station 2 (pas de calcul si c'est lui le premier)
		--
		-- Récupération du meilleur temps entre la station 1 et la 2
		SELECT idREUR, tempsInter INTO t_IdREURChampion, t_tempsInterChampion FROM chrono WHERE idURSE=OLD.idURSE && tempsInter!='00:00:00' ORDER BY tempsInter LIMIT 0,1;
		-- Si y'a un coureur qui a déjà franchi la station 2 et si son temps est meilleur que le coureur actuel
		IF t_IdREURChampion IS NOT NULL AND t_tempsInterChampion < STR_TO_DATE(t_tempInter, '%H:%i:%s') THEN
			-- Conversion du temps inter du champion en secondes
			SET t_tempsInterChampionEnSec := HOUR(t_tempsInterChampion) * 3600;
			SET t_tempsInterChampionEnSec := t_tempsInterChampionEnSec + MINUTE(t_tempsInterChampion) * 60;
			SET t_tempsInterChampionEnSec := t_tempsInterChampionEnSec + SECOND(t_tempsInterChampion);
			-- Conversion du temps inter du coureur actuelle en secondes
			SET t_tempsInterREURActuEnSec := HOUR(t_tempInter) * 3600;
			SET t_tempsInterREURActuEnSec := t_tempsInterREURActuEnSec + MINUTE(t_tempInter) * 60;
			SET t_tempsInterREURActuEnSec := t_tempsInterREURActuEnSec + SECOND(t_tempInter);
			
			-- Calcul de l'ecart du coureur actuel avec le champion à mi-parcours (station 2)
			SET t_totalTempsEnSec := t_tempsInterREURActuEnSec - t_tempsInterChampionEnSec;
			SET t_min := t_totalTempsEnSec / 60;
			SET t_sec := MOD(t_totalTempsEnSec, 60);
			SET t_heure := t_min / 60;
			SET t_min := MOD(t_min, 60);
			SET	t_tempDiff := CONCAT(t_heure, ':', t_min, ':', t_sec);
			-- Maj du champ difference du coureur dans la table chrono en bdd
			SET NEW.difference := t_tempDiff;
		ELSE
			IF t_IdREURChampion IS NOT NULL THEN
				-- Conversion du temps inter de l'ancien meilleur temps en secondes
				SET t_tempsInterChampionEnSec := HOUR(t_tempsInterChampion) * 3600;
				SET t_tempsInterChampionEnSec := t_tempsInterChampionEnSec + MINUTE(t_tempsInterChampion) * 60;
				SET t_tempsInterChampionEnSec := t_tempsInterChampionEnSec + SECOND(t_tempsInterChampion);
				-- Conversion du temps inter du coureur actuelle (meilleur temps à présent) en secondes
				SET t_tempsInterREURActuEnSec := HOUR(t_tempInter) * 3600;
				SET t_tempsInterREURActuEnSec := t_tempsInterREURActuEnSec + MINUTE(t_tempInter) * 60;
				SET t_tempsInterREURActuEnSec := t_tempsInterREURActuEnSec + SECOND(t_tempInter);
				
				-- Calcul de l'ecart du coureur qui avait le meilleur temps avec le nouveau meilleur coureur actuel qui devient le champion à mi-parcours (station 2)
				SET t_totalTempsEnSec := t_tempsInterChampionEnSec - t_tempsInterREURActuEnSec;
				SET t_min := t_totalTempsEnSec / 60;
				SET t_sec := MOD(t_totalTempsEnSec, 60);
				SET t_heure := t_min / 60;
				SET t_min := MOD(t_min, 60);
				SET	t_tempDiff := CONCAT(t_heure, ':', t_min, ':', t_sec);
				-- Maj du champ difference du coureur dans la table chrono en bdd
				UPDATE chrono SET difference = t_tempDiff WHERE t_idREUR = t_IdREURChampion;
			END IF;
		END IF;
	END IF;
	--
	IF OLD.t_arrivee<>NEW.t_arrivee THEN
		-- Conversion de l'heure de passage du coureur à la station 1 (depart) en secondes
		SET t_heureDepartEnSec := HOUR(NEW.t_depart) * 3600;
		SET t_heureDepartEnSec := t_heureDepartEnSec + MINUTE(NEW.t_depart) * 60;
		SET t_heureDepartEnSec := t_heureDepartEnSec + SECOND(NEW.t_depart);
		-- Conversion de l'heure de passage du coureur à la station 3 (arrivee) en secondes
		SET t_heureArriveEnSec := HOUR(NEW.t_arrivee) * 3600;
		SET t_heureArriveEnSec := t_heureArriveEnSec + MINUTE(NEW.t_arrivee) * 60;
		SET t_heureArriveEnSec := t_heureArriveEnSec + SECOND(NEW.t_arrivee);
		-- Calcul du temps mis par le coureur entre la station 1 (depart) et la 3 (arrivee)
		SET t_totalTempsEnSec := t_heureArriveEnSec - t_heureDepartEnSec;
		SET t_min := t_totalTempsEnSec / 60;
		SET t_sec := MOD(t_totalTempsEnSec, 60);
		SET t_heure := t_min / 60;
		SET t_min := MOD(t_min, 60);
		SET	t_tempFinal := CONCAT(t_heure, ':', t_min, ':', t_sec);
		-- Maj du champ tempsFinal du coureur dans la table chrono en bdd
		SET NEW.tempsFinal := t_tempFinal;
	END IF;
--
END $$
DELIMITER ;
