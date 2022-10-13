#pragma once

namespace Chrono_ng {

	using namespace System;
	using namespace System::ComponentModel;
	using namespace System::Collections;
	using namespace System::Windows::Forms;
	using namespace System::Data;
	using namespace System::Drawing;
	using namespace MySql::Data::MySqlClient;
	using namespace System::Net;
	using namespace System::IO;

	/// <summary>
	/// Description résumée de Direct
	/// </summary>
	public ref class Direct : public System::Windows::Forms::Form
	{
		MySqlConnection^ _conn;

	public:
		Direct(MySqlConnection^ conn)
		{
			InitializeComponent();
			//
			//TODO: ajoutez ici le code du constructeur
			//
			_conn = conn;  // On récupère la connexion à la base de donnée qui est déja ouvert

			String^ date = DateTime::Now.Today.ToString("d"); // On récupère la date de l'ordinateur
			String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1]; //Inverser date
			String^ requete = "SELECT nomURSE FROM course WHERE dateURSE='" + dateInverser + "'";
			MySqlCommand^ command = gcnew MySqlCommand(requete, conn);
			MySqlDataReader^ reader = command->ExecuteReader();
			
			if (reader->Read()) // si course afficher son nom sur le label
			{
				labelCourse->Text = reader->GetString(0);
			}
			else
			{
				labelCourse->Text = "";
				labelHeure->Text = "";
				labelEnDirect->Text = "Aucune course aujourd'hui";
				timerDirect->Enabled = false; // on désactive le timer
				timerHeure->Enabled = false;
			}
			reader->Close();
			AfficheClassement();
		}

	protected:
		/// <summary>
		/// Nettoyage des ressources utilisées.
		/// </summary>
		~Direct()
		{
			if (components)
			{
				delete components;
			}
		}

	private: System::Windows::Forms::DataGridView^  dataGridViewDirect;
	private: System::Windows::Forms::Timer^  timerDirect;
	private: System::Windows::Forms::Label^  labelEnDirect;
	private: System::Windows::Forms::Label^  labelHeure;
	private: System::Windows::Forms::Label^  labelCourse;
	private: System::Windows::Forms::Timer^  timerHeure;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_place;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_nom;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_prenom;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_sexe;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_dossard;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_temps;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_difference;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_situation;








































	private: System::ComponentModel::IContainer^  components;
	private:
		/// <summary>
		/// Variable nécessaire au concepteur.
		/// </summary>


#pragma region Windows Form Designer generated code
		/// <summary>
		/// Méthode requise pour la prise en charge du concepteur - ne modifiez pas
		/// le contenu de cette méthode avec l'éditeur de code.
		/// </summary>
		void InitializeComponent(void)
		{
			this->components = (gcnew System::ComponentModel::Container());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle1 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle10 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle11 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle2 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle3 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle4 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle5 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle6 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle7 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle8 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle9 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::ComponentModel::ComponentResourceManager^  resources = (gcnew System::ComponentModel::ComponentResourceManager(Direct::typeid));
			this->dataGridViewDirect = (gcnew System::Windows::Forms::DataGridView());
			this->timerDirect = (gcnew System::Windows::Forms::Timer(this->components));
			this->labelEnDirect = (gcnew System::Windows::Forms::Label());
			this->labelHeure = (gcnew System::Windows::Forms::Label());
			this->labelCourse = (gcnew System::Windows::Forms::Label());
			this->timerHeure = (gcnew System::Windows::Forms::Timer(this->components));
			this->datagrid_place = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_nom = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_prenom = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_sexe = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_dossard = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_temps = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_difference = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_situation = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			(cli::safe_cast<System::ComponentModel::ISupportInitialize^>(this->dataGridViewDirect))->BeginInit();
			this->SuspendLayout();
			// 
			// dataGridViewDirect
			// 
			this->dataGridViewDirect->AllowUserToAddRows = false;
			this->dataGridViewDirect->AllowUserToDeleteRows = false;
			dataGridViewCellStyle1->BackColor = System::Drawing::Color::FromArgb(static_cast<System::Int32>(static_cast<System::Byte>(248)),
				static_cast<System::Int32>(static_cast<System::Byte>(240)), static_cast<System::Int32>(static_cast<System::Byte>(167)));
			this->dataGridViewDirect->AlternatingRowsDefaultCellStyle = dataGridViewCellStyle1;
			this->dataGridViewDirect->BackgroundColor = System::Drawing::Color::White;
			this->dataGridViewDirect->BorderStyle = System::Windows::Forms::BorderStyle::Fixed3D;
			this->dataGridViewDirect->ColumnHeadersHeightSizeMode = System::Windows::Forms::DataGridViewColumnHeadersHeightSizeMode::AutoSize;
			this->dataGridViewDirect->Columns->AddRange(gcnew cli::array< System::Windows::Forms::DataGridViewColumn^  >(8) {
				this->datagrid_place,
					this->datagrid_nom, this->datagrid_prenom, this->datagrid_sexe, this->datagrid_dossard, this->datagrid_temps, this->datagrid_difference,
					this->datagrid_situation
			});
			dataGridViewCellStyle10->Alignment = System::Windows::Forms::DataGridViewContentAlignment::MiddleLeft;
			dataGridViewCellStyle10->BackColor = System::Drawing::SystemColors::Window;
			dataGridViewCellStyle10->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 11.25F, System::Drawing::FontStyle::Regular,
				System::Drawing::GraphicsUnit::Point, static_cast<System::Byte>(0)));
			dataGridViewCellStyle10->ForeColor = System::Drawing::SystemColors::ControlText;
			dataGridViewCellStyle10->SelectionBackColor = System::Drawing::SystemColors::Highlight;
			dataGridViewCellStyle10->SelectionForeColor = System::Drawing::SystemColors::HighlightText;
			dataGridViewCellStyle10->WrapMode = System::Windows::Forms::DataGridViewTriState::False;
			this->dataGridViewDirect->DefaultCellStyle = dataGridViewCellStyle10;
			this->dataGridViewDirect->Location = System::Drawing::Point(376, 131);
			this->dataGridViewDirect->Name = L"dataGridViewDirect";
			this->dataGridViewDirect->ReadOnly = true;
			dataGridViewCellStyle11->BackColor = System::Drawing::SystemColors::Control;
			dataGridViewCellStyle11->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 8.25F, System::Drawing::FontStyle::Regular,
				System::Drawing::GraphicsUnit::Point, static_cast<System::Byte>(0)));
			dataGridViewCellStyle11->ForeColor = System::Drawing::SystemColors::WindowText;
			dataGridViewCellStyle11->SelectionBackColor = System::Drawing::SystemColors::Highlight;
			dataGridViewCellStyle11->SelectionForeColor = System::Drawing::SystemColors::HighlightText;
			this->dataGridViewDirect->RowHeadersDefaultCellStyle = dataGridViewCellStyle11;
			this->dataGridViewDirect->ScrollBars = System::Windows::Forms::ScrollBars::Vertical;
			this->dataGridViewDirect->Size = System::Drawing::Size(683, 445);
			this->dataGridViewDirect->TabIndex = 0;
			// 
			// timerDirect
			// 
			this->timerDirect->Enabled = true;
			this->timerDirect->Interval = 2000;
			this->timerDirect->Tick += gcnew System::EventHandler(this, &Direct::timerDirect_Tick);
			// 
			// labelEnDirect
			// 
			this->labelEnDirect->AutoSize = true;
			this->labelEnDirect->BackColor = System::Drawing::Color::Gold;
			this->labelEnDirect->BorderStyle = System::Windows::Forms::BorderStyle::FixedSingle;
			this->labelEnDirect->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 15.75F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->labelEnDirect->ForeColor = System::Drawing::SystemColors::ControlText;
			this->labelEnDirect->Location = System::Drawing::Point(543, 69);
			this->labelEnDirect->Name = L"labelEnDirect";
			this->labelEnDirect->Size = System::Drawing::Size(126, 27);
			this->labelEnDirect->TabIndex = 1;
			this->labelEnDirect->Text = L"EN DIRECT";
			// 
			// labelHeure
			// 
			this->labelHeure->AutoSize = true;
			this->labelHeure->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 15.75F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->labelHeure->Location = System::Drawing::Point(882, 71);
			this->labelHeure->Name = L"labelHeure";
			this->labelHeure->Size = System::Drawing::Size(96, 25);
			this->labelHeure->TabIndex = 18;
			this->labelHeure->Text = L"00:00:00";
			// 
			// labelCourse
			// 
			this->labelCourse->AutoSize = true;
			this->labelCourse->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->labelCourse->Location = System::Drawing::Point(675, 76);
			this->labelCourse->Name = L"labelCourse";
			this->labelCourse->Size = System::Drawing::Size(159, 16);
			this->labelCourse->TabIndex = 19;
			this->labelCourse->Text = L"Course de Strasbourg";
			// 
			// timerHeure
			// 
			this->timerHeure->Enabled = true;
			this->timerHeure->Interval = 1000;
			this->timerHeure->Tick += gcnew System::EventHandler(this, &Direct::timerHeure_Tick);
			// 
			// datagrid_place
			// 
			dataGridViewCellStyle2->Alignment = System::Windows::Forms::DataGridViewContentAlignment::MiddleCenter;
			dataGridViewCellStyle2->Font = (gcnew System::Drawing::Font(L"Arial", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->datagrid_place->DefaultCellStyle = dataGridViewCellStyle2;
			this->datagrid_place->HeaderText = L"Place";
			this->datagrid_place->Name = L"datagrid_place";
			this->datagrid_place->ReadOnly = true;
			this->datagrid_place->Width = 70;
			// 
			// datagrid_nom
			// 
			dataGridViewCellStyle3->Font = (gcnew System::Drawing::Font(L"Arial", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->datagrid_nom->DefaultCellStyle = dataGridViewCellStyle3;
			this->datagrid_nom->HeaderText = L"Nom";
			this->datagrid_nom->Name = L"datagrid_nom";
			this->datagrid_nom->ReadOnly = true;
			// 
			// datagrid_prenom
			// 
			dataGridViewCellStyle4->Font = (gcnew System::Drawing::Font(L"Arial", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->datagrid_prenom->DefaultCellStyle = dataGridViewCellStyle4;
			this->datagrid_prenom->HeaderText = L"Prenom";
			this->datagrid_prenom->Name = L"datagrid_prenom";
			this->datagrid_prenom->ReadOnly = true;
			// 
			// datagrid_sexe
			// 
			dataGridViewCellStyle5->Alignment = System::Windows::Forms::DataGridViewContentAlignment::MiddleCenter;
			dataGridViewCellStyle5->Font = (gcnew System::Drawing::Font(L"Arial", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->datagrid_sexe->DefaultCellStyle = dataGridViewCellStyle5;
			this->datagrid_sexe->HeaderText = L"Sexe";
			this->datagrid_sexe->Name = L"datagrid_sexe";
			this->datagrid_sexe->ReadOnly = true;
			this->datagrid_sexe->Width = 50;
			// 
			// datagrid_dossard
			// 
			dataGridViewCellStyle6->Font = (gcnew System::Drawing::Font(L"Arial", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->datagrid_dossard->DefaultCellStyle = dataGridViewCellStyle6;
			this->datagrid_dossard->HeaderText = L"Dossard";
			this->datagrid_dossard->Name = L"datagrid_dossard";
			this->datagrid_dossard->ReadOnly = true;
			this->datagrid_dossard->Width = 70;
			// 
			// datagrid_temps
			// 
			dataGridViewCellStyle7->Font = (gcnew System::Drawing::Font(L"Arial", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->datagrid_temps->DefaultCellStyle = dataGridViewCellStyle7;
			this->datagrid_temps->HeaderText = L"Temps";
			this->datagrid_temps->Name = L"datagrid_temps";
			this->datagrid_temps->ReadOnly = true;
			this->datagrid_temps->Width = 85;
			// 
			// datagrid_difference
			// 
			dataGridViewCellStyle8->Font = (gcnew System::Drawing::Font(L"Arial", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->datagrid_difference->DefaultCellStyle = dataGridViewCellStyle8;
			this->datagrid_difference->HeaderText = L"Difference";
			this->datagrid_difference->Name = L"datagrid_difference";
			this->datagrid_difference->ReadOnly = true;
			this->datagrid_difference->Width = 65;
			// 
			// datagrid_situation
			// 
			dataGridViewCellStyle9->Font = (gcnew System::Drawing::Font(L"Arial", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->datagrid_situation->DefaultCellStyle = dataGridViewCellStyle9;
			this->datagrid_situation->HeaderText = L"Situation";
			this->datagrid_situation->Name = L"datagrid_situation";
			this->datagrid_situation->ReadOnly = true;
			// 
			// Direct
			// 
			this->AutoScaleDimensions = System::Drawing::SizeF(6, 13);
			this->AutoScaleMode = System::Windows::Forms::AutoScaleMode::Font;
			this->ClientSize = System::Drawing::Size(1370, 639);
			this->Controls->Add(this->labelCourse);
			this->Controls->Add(this->labelHeure);
			this->Controls->Add(this->labelEnDirect);
			this->Controls->Add(this->dataGridViewDirect);
			this->Icon = (cli::safe_cast<System::Drawing::Icon^>(resources->GetObject(L"$this.Icon")));
			this->Name = L"Direct";
			this->StartPosition = System::Windows::Forms::FormStartPosition::CenterScreen;
			this->Text = L"Direct";
			(cli::safe_cast<System::ComponentModel::ISupportInitialize^>(this->dataGridViewDirect))->EndInit();
			this->ResumeLayout(false);
			this->PerformLayout();

		}
#pragma endregion
		void AfficheClassement()
		{
			dataGridViewDirect->Columns[6]->DefaultCellStyle->ForeColor = Color::Green; // pour les differences en vert
			String^ date = DateTime::Now.Today.ToString("d"); // On récupère la date de l'ordinateur
			String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1]; //Inverser date
			
			/*---------- On récupère en premier que ceux qui ont franchi la ligne d'arrivée -------------------------------*/
			String^ requeteArrivee = "SELECT coureur.nomREUR, coureur.prenomREUR, coureur.sexeREUR, dossard.numSARD, chrono.tempsFinal, chrono.difference FROM (((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idURSE=course.idURSE AND dossard.idREUR=coureur.idREUR) WHERE course.dateURSE='" + dateInverser + "' AND chrono.t_arrivee!='00:00:00' ORDER BY chrono.tempsFinal";
			MySqlCommand^ commandArrivee = gcnew MySqlCommand(requeteArrivee, _conn);
			MySqlDataReader^ readerArrivee = commandArrivee->ExecuteReader();
			int place = 1; // pour place des coureurs
			while (readerArrivee->Read()) // lit par ligne
			{
				String^ recup_difference = readerArrivee->GetString(5);
				String^ difference;
				// On mets les unités devant
				if (recup_difference[0] == '0' && recup_difference[1] == '0' && recup_difference[3] == '0' && recup_difference[4] == '0' && recup_difference[6] == '0' && recup_difference[7] == '0')
					difference = "+0s";
				else if (recup_difference[0] == '0' && recup_difference[1] == '0' && recup_difference[3] == '0' && recup_difference[4] == '0')
					difference = "+" + recup_difference[6] + recup_difference[7] + "s";
				else if (recup_difference[0] == '0' && recup_difference[1] == '0')
					difference = "+" + recup_difference[3] + recup_difference[4] + "m" + recup_difference[6] + recup_difference[7] + "s";
				else if (recup_difference[0] != '0' || recup_difference[1] != '0')
					difference = "+" + recup_difference[0] + recup_difference[1] + "h" + recup_difference[3] + recup_difference[4] + "m" + recup_difference[6] + recup_difference[7] + "s";
				
				// Temps
				String^ recupTemps = readerArrivee->GetString(4);
				// Obliger de mettre "" + devant car bug
				String^ temps = "" + recupTemps[0] + recupTemps[1] + "h" + recupTemps[3] + recupTemps[4] + "m" + recupTemps[6] + recupTemps[7] + "s";
				// On mets le classement dans le tableau
				if (place > 1) // si coureurs poursuivants, mettre difference
					dataGridViewDirect->Rows->Add(place, readerArrivee->GetString(0), readerArrivee->GetString(1), readerArrivee->GetString(2), readerArrivee->GetString(3), temps, difference, "Terminé");
				else // si premier coureur, ne pas mettre difference puisque il est le premier a passé à la station
					dataGridViewDirect->Rows->Add(place, readerArrivee->GetString(0), readerArrivee->GetString(1), readerArrivee->GetString(2), readerArrivee->GetString(3), temps, "", "Terminé");
				place++;
			}
			readerArrivee->Close(); // fermer le datareader sinon risque pertubation avec les autre datareader

			/*----------- Ensuite on récupère en deuxième que ceux qui ont franchi le passage intermédiaire -----------------------*/
			String^ requeteInter = "SELECT coureur.nomREUR, coureur.prenomREUR, coureur.sexeREUR, dossard.numSARD, chrono.tempsInter, chrono.difference FROM (((chrono INNER JOIN coureur ON chrono.idREUR=coureur.idREUR) INNER JOIN course ON chrono.idURSE=course.idURSE) INNER JOIN dossard ON dossard.idURSE=course.idURSE AND dossard.idREUR=coureur.idREUR) WHERE course.dateURSE='" + dateInverser + "' AND chrono.t_arrivee='00:00:00' AND chrono.t_inter!='00:00:00' ORDER BY chrono.tempsInter";
			MySqlCommand^ commandInter = gcnew MySqlCommand(requeteInter, _conn);
			MySqlDataReader^ readerInter = commandInter->ExecuteReader();
			while (readerInter->Read())
			{
				String^ recup_difference = readerInter->GetString(5);
				String^ difference;
				// On mets les unités devant
				if (recup_difference[0] == '0' && recup_difference[1] == '0' && recup_difference[3] == '0' && recup_difference[4] == '0' && recup_difference[6] == '0' && recup_difference[7] == '0')
					difference = "+0s";
				else if (recup_difference[0] == '0' && recup_difference[1] == '0' && recup_difference[3] == '0' && recup_difference[4] == '0')
					difference = "+" + recup_difference[6] + recup_difference[7] + "s";
				else if (recup_difference[0] == '0' && recup_difference[1] == '0')
					difference = "+" + recup_difference[3] + recup_difference[4] + "m" + recup_difference[6] + recup_difference[7] + "s";
				else if (recup_difference[0] != '0' || recup_difference[1] != '0')
					difference = "+" + recup_difference[0] + recup_difference[1] + "h" + recup_difference[3] + recup_difference[4] + "m" + recup_difference[6] + recup_difference[7] + "s";
				// temps
				String^ recupTemps = readerInter->GetString(4);
				// Obliger de mettre "" + devant car bug
				String^ temps = "" + recupTemps[0] + recupTemps[1] + "h" + recupTemps[3] + recupTemps[4] + "m" + recupTemps[6] + recupTemps[7] + "s";
				// On mets le classement dans le tableau
				if (place > 1) // si coureurs poursuivants, mettre difference
					dataGridViewDirect->Rows->Add(place, readerInter->GetString(0), readerInter->GetString(1), readerInter->GetString(2), readerInter->GetString(3), temps, difference, "Mi-Parcours");
				else // si premier coureur, ne pas mettre difference puisque il est le premier a passé à la station
					dataGridViewDirect->Rows->Add(place, readerInter->GetString(0), readerInter->GetString(1), readerInter->GetString(2), readerInter->GetString(3), temps, "", "Mi-Parcours");
				place++;
			}
			readerInter->Close();
		}

// pour rafraichir le classement tous les n secondes
private: System::Void timerDirect_Tick(System::Object^  sender, System::EventArgs^  e) {
	dataGridViewDirect->Rows->Clear();
	AfficheClassement();
}

// pour rafraichir l'heure 
private: System::Void timerHeure_Tick(System::Object^  sender, System::EventArgs^  e) {
	String^ heure;
	if (DateTime::Now.Hour <= 9)
	{
		heure = "0" + DateTime::Now.Hour.ToString();
	}
	else
	{
		heure = DateTime::Now.Hour.ToString();
	}

	if (DateTime::Now.Minute <= 9)
	{
		heure += ":0" + DateTime::Now.Minute.ToString();
	}
	else
	{
		heure += ":" + DateTime::Now.Minute.ToString();
	}

	if (DateTime::Now.Second <= 9)
	{
		heure += ":0" + DateTime::Now.Second.ToString();
	}
	else
	{
		heure += ":" + DateTime::Now.Second.ToString();
	}
	labelHeure->Text = heure;
}

};
}
