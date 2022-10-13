#pragma once
#include "Direct.h"

namespace Chrono_ng {

	using namespace System;
	using namespace System::ComponentModel;
	using namespace System::Collections;
	using namespace System::Windows::Forms;
	using namespace System::Data;
	using namespace System::Drawing;
	using namespace System::IO::Ports;
	using namespace MySql::Data::MySqlClient;

	/// <summary>
	/// Summary for MyForm
	/// </summary>

	// On crée un délégué
	delegate void DelegateRFID();

	public ref class Enregistrement : public System::Windows::Forms::Form
	{		
		MySqlConnection^ _conn;
		SerialPort^ _serialPortRFID;
		DelegateRFID^ _monDelegateRFID;
	    bool _connecteBddReussi;
	private: System::Windows::Forms::TextBox^  nbrAssociation;



			 bool _lecteurBadgeConnecter;

	public:
		Enregistrement(void)
		{
			InitializeComponent();
			//
			//TODO: Add the constructor code here
			//
			/*--# Affichage de tous les ports de la machine actuelle dans la liste déroulante #--*/
			for each (String^ port in SerialPort::GetPortNames())
			{
				comboBox_Port->Items->Add(port);
			}
			_lecteurBadgeConnecter = false;

			//String^ requeteDeConnection = "server=localhost;uid=root;password='';database=chrono_ng;";
			//String^ requeteDeConnection = "server=172.20.10.2;uid=Vai;password='';database=chrono_ng;";
			String^ requeteDeConnection = "server=mysql-chrono.alwaysdata.net;uid=chrono;password='chrono';database=chrono_ng;";
			_conn = gcnew MySqlConnection(requeteDeConnection);
				try
				{
					_conn->Open();  // Connexion à la base de données
					_connecteBddReussi = true;
					AfficherTout(); // affiche les associations RFID déja faites
					nbrAssociation->Text = dataGridView1->RowCount.ToString(); // affiche sur label_nbrAssociation le nombre d'association RFID
					SuggestionNom(); // on initialise le tableau de suggestion des noms des coureurs
				}
				catch (Exception^ ex)
				{
					_connecteBddReussi = false;
					buttonDirect->Visible = false;
					pictureBoxNoConnect->Visible = true;
				}
				
				_monDelegateRFID += gcnew DelegateRFID(this, &Enregistrement::LireRFID);
		}

	protected:
		/// <summary>
		/// Clean up any resources being used.
		/// </summary>
		~Enregistrement()
		{
			if (components)
			{
				delete components;
			}
		}
	
	private: System::Windows::Forms::TextBox^  textBoxRFID;
	private: System::Windows::Forms::TextBox^  textBoxSARD;
	private: System::Windows::Forms::TextBox^  textBoxNomREUR;
	private: System::Windows::Forms::Label^  labelRFID;
	private: System::Windows::Forms::Label^  labelSARD;
	private: System::Windows::Forms::Label^  labelNomREUR;
	private: System::ComponentModel::IContainer^  components;
	private: System::Windows::Forms::TextBox^  textBoxPrenomREUR;
	private: System::Windows::Forms::Label^  labelPrenomREUR;
	private: System::Windows::Forms::DataGridView^  dataGridView1;
	private: System::Windows::Forms::Button^  buttonModifier;
	private: System::Windows::Forms::Button^  buttonRechercher;
	private: System::Windows::Forms::GroupBox^  groupBox2;
	private: System::Windows::Forms::Label^  label1;
	private: System::Windows::Forms::TextBox^  textBoxID;
	private: System::Windows::Forms::Label^  labelID;
	private: System::Windows::Forms::TextBox^  textBoxModifDossard;
	private: System::Windows::Forms::Label^  labelModifDossard;
	private: System::Windows::Forms::GroupBox^  groupBox3;
	private: System::Windows::Forms::GroupBox^  groupBox1;
	private: System::Windows::Forms::PictureBox^  pictureBoxNoConnect;
	private: System::Windows::Forms::Label^  labelHeure;
	private: System::Windows::Forms::Timer^  timerHeure;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_id;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_NomREUR;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_PrenomREUR;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_numSARD;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_numRFID;
	private: System::Windows::Forms::DataGridViewTextBoxColumn^  datagrid_NomURSE;
	private: System::Windows::Forms::Button^  buttonDirect;
	private: System::Windows::Forms::ComboBox^  comboBox_Port;
	private: System::Windows::Forms::Label^  label_Port;
	private: System::Windows::Forms::Button^  button_Ouvrir;
	private: System::Windows::Forms::GroupBox^  groupBox_port;
	private: System::Windows::Forms::Button^  button_Fermer;


	protected:

	private:
		/// <summary>
		/// Required designer variable.
		/// </summary>


#pragma region Windows Form Designer generated code
		/// <summary>
		/// Required method for Designer support - do not modify
		/// the contents of this method with the code editor.
		/// </summary>
		void InitializeComponent(void)
		{
			this->components = (gcnew System::ComponentModel::Container());
			System::Windows::Forms::DataGridViewCellStyle^  dataGridViewCellStyle1 = (gcnew System::Windows::Forms::DataGridViewCellStyle());
			System::ComponentModel::ComponentResourceManager^  resources = (gcnew System::ComponentModel::ComponentResourceManager(Enregistrement::typeid));
			this->textBoxRFID = (gcnew System::Windows::Forms::TextBox());
			this->textBoxSARD = (gcnew System::Windows::Forms::TextBox());
			this->textBoxNomREUR = (gcnew System::Windows::Forms::TextBox());
			this->labelRFID = (gcnew System::Windows::Forms::Label());
			this->labelSARD = (gcnew System::Windows::Forms::Label());
			this->labelNomREUR = (gcnew System::Windows::Forms::Label());
			this->textBoxPrenomREUR = (gcnew System::Windows::Forms::TextBox());
			this->labelPrenomREUR = (gcnew System::Windows::Forms::Label());
			this->groupBox1 = (gcnew System::Windows::Forms::GroupBox());
			this->buttonRechercher = (gcnew System::Windows::Forms::Button());
			this->labelID = (gcnew System::Windows::Forms::Label());
			this->textBoxID = (gcnew System::Windows::Forms::TextBox());
			this->buttonModifier = (gcnew System::Windows::Forms::Button());
			this->dataGridView1 = (gcnew System::Windows::Forms::DataGridView());
			this->datagrid_id = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_NomREUR = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_PrenomREUR = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_numSARD = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_numRFID = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->datagrid_NomURSE = (gcnew System::Windows::Forms::DataGridViewTextBoxColumn());
			this->groupBox2 = (gcnew System::Windows::Forms::GroupBox());
			this->nbrAssociation = (gcnew System::Windows::Forms::TextBox());
			this->label1 = (gcnew System::Windows::Forms::Label());
			this->textBoxModifDossard = (gcnew System::Windows::Forms::TextBox());
			this->labelModifDossard = (gcnew System::Windows::Forms::Label());
			this->groupBox3 = (gcnew System::Windows::Forms::GroupBox());
			this->pictureBoxNoConnect = (gcnew System::Windows::Forms::PictureBox());
			this->labelHeure = (gcnew System::Windows::Forms::Label());
			this->timerHeure = (gcnew System::Windows::Forms::Timer(this->components));
			this->buttonDirect = (gcnew System::Windows::Forms::Button());
			this->comboBox_Port = (gcnew System::Windows::Forms::ComboBox());
			this->label_Port = (gcnew System::Windows::Forms::Label());
			this->button_Ouvrir = (gcnew System::Windows::Forms::Button());
			this->groupBox_port = (gcnew System::Windows::Forms::GroupBox());
			this->button_Fermer = (gcnew System::Windows::Forms::Button());
			this->groupBox1->SuspendLayout();
			(cli::safe_cast<System::ComponentModel::ISupportInitialize^>(this->dataGridView1))->BeginInit();
			this->groupBox2->SuspendLayout();
			this->groupBox3->SuspendLayout();
			(cli::safe_cast<System::ComponentModel::ISupportInitialize^>(this->pictureBoxNoConnect))->BeginInit();
			this->groupBox_port->SuspendLayout();
			this->SuspendLayout();
			// 
			// textBoxRFID
			// 
			this->textBoxRFID->BackColor = System::Drawing::Color::White;
			this->textBoxRFID->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 12, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->textBoxRFID->ForeColor = System::Drawing::SystemColors::WindowText;
			this->textBoxRFID->Location = System::Drawing::Point(744, 48);
			this->textBoxRFID->Name = L"textBoxRFID";
			this->textBoxRFID->Size = System::Drawing::Size(204, 26);
			this->textBoxRFID->TabIndex = 15;
			this->textBoxRFID->TextAlign = System::Windows::Forms::HorizontalAlignment::Center;
			// 
			// textBoxSARD
			// 
			this->textBoxSARD->CharacterCasing = System::Windows::Forms::CharacterCasing::Upper;
			this->textBoxSARD->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 12, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->textBoxSARD->Location = System::Drawing::Point(503, 48);
			this->textBoxSARD->Name = L"textBoxSARD";
			this->textBoxSARD->Size = System::Drawing::Size(204, 26);
			this->textBoxSARD->TabIndex = 3;
			this->textBoxSARD->TextAlign = System::Windows::Forms::HorizontalAlignment::Center;
			// 
			// textBoxNomREUR
			// 
			this->textBoxNomREUR->AutoCompleteMode = System::Windows::Forms::AutoCompleteMode::Suggest;
			this->textBoxNomREUR->AutoCompleteSource = System::Windows::Forms::AutoCompleteSource::CustomSource;
			this->textBoxNomREUR->BackColor = System::Drawing::SystemColors::Window;
			this->textBoxNomREUR->CharacterCasing = System::Windows::Forms::CharacterCasing::Upper;
			this->textBoxNomREUR->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 12, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->textBoxNomREUR->Location = System::Drawing::Point(24, 48);
			this->textBoxNomREUR->Name = L"textBoxNomREUR";
			this->textBoxNomREUR->Size = System::Drawing::Size(204, 26);
			this->textBoxNomREUR->TabIndex = 1;
			this->textBoxNomREUR->TextAlign = System::Windows::Forms::HorizontalAlignment::Center;
			this->textBoxNomREUR->TextChanged += gcnew System::EventHandler(this, &Enregistrement::textBoxNomREUR_TextChanged);
			// 
			// labelRFID
			// 
			this->labelRFID->AutoSize = true;
			this->labelRFID->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 8.25F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->labelRFID->Location = System::Drawing::Point(804, 22);
			this->labelRFID->Name = L"labelRFID";
			this->labelRFID->Size = System::Drawing::Size(72, 13);
			this->labelRFID->TabIndex = 3;
			this->labelRFID->Text = L"Numéro RFID";
			// 
			// labelSARD
			// 
			this->labelSARD->AutoSize = true;
			this->labelSARD->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 8.25F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->labelSARD->Location = System::Drawing::Point(564, 22);
			this->labelSARD->Name = L"labelSARD";
			this->labelSARD->Size = System::Drawing::Size(86, 13);
			this->labelSARD->TabIndex = 4;
			this->labelSARD->Text = L"Numéro Dossard";
			// 
			// labelNomREUR
			// 
			this->labelNomREUR->AutoSize = true;
			this->labelNomREUR->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 8.25F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->labelNomREUR->Location = System::Drawing::Point(90, 22);
			this->labelNomREUR->Name = L"labelNomREUR";
			this->labelNomREUR->Size = System::Drawing::Size(84, 13);
			this->labelNomREUR->TabIndex = 5;
			this->labelNomREUR->Text = L"Nom du Coureur";
			// 
			// textBoxPrenomREUR
			// 
			this->textBoxPrenomREUR->AutoCompleteMode = System::Windows::Forms::AutoCompleteMode::Suggest;
			this->textBoxPrenomREUR->AutoCompleteSource = System::Windows::Forms::AutoCompleteSource::CustomSource;
			this->textBoxPrenomREUR->CharacterCasing = System::Windows::Forms::CharacterCasing::Upper;
			this->textBoxPrenomREUR->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 12, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->textBoxPrenomREUR->Location = System::Drawing::Point(263, 48);
			this->textBoxPrenomREUR->Name = L"textBoxPrenomREUR";
			this->textBoxPrenomREUR->Size = System::Drawing::Size(204, 26);
			this->textBoxPrenomREUR->TabIndex = 2;
			this->textBoxPrenomREUR->TextAlign = System::Windows::Forms::HorizontalAlignment::Center;
			this->textBoxPrenomREUR->Enter += gcnew System::EventHandler(this, &Enregistrement::textBoxPrenomREUR_Enter);
			// 
			// labelPrenomREUR
			// 
			this->labelPrenomREUR->AutoSize = true;
			this->labelPrenomREUR->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 8.25F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->labelPrenomREUR->Location = System::Drawing::Point(312, 22);
			this->labelPrenomREUR->Name = L"labelPrenomREUR";
			this->labelPrenomREUR->Size = System::Drawing::Size(98, 13);
			this->labelPrenomREUR->TabIndex = 6;
			this->labelPrenomREUR->Text = L"Prénom du Coureur";
			// 
			// groupBox1
			// 
			this->groupBox1->BackColor = System::Drawing::SystemColors::ActiveCaption;
			this->groupBox1->Controls->Add(this->buttonRechercher);
			this->groupBox1->Controls->Add(this->labelRFID);
			this->groupBox1->Controls->Add(this->labelPrenomREUR);
			this->groupBox1->Controls->Add(this->textBoxNomREUR);
			this->groupBox1->Controls->Add(this->textBoxPrenomREUR);
			this->groupBox1->Controls->Add(this->textBoxRFID);
			this->groupBox1->Controls->Add(this->textBoxSARD);
			this->groupBox1->Controls->Add(this->labelNomREUR);
			this->groupBox1->Controls->Add(this->labelSARD);
			this->groupBox1->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 8.25F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->groupBox1->Location = System::Drawing::Point(294, 36);
			this->groupBox1->Name = L"groupBox1";
			this->groupBox1->Size = System::Drawing::Size(973, 179);
			this->groupBox1->TabIndex = 7;
			this->groupBox1->TabStop = false;
			this->groupBox1->Text = L"Association des badges RFID";
			// 
			// buttonRechercher
			// 
			this->buttonRechercher->Cursor = System::Windows::Forms::Cursors::Hand;
			this->buttonRechercher->Location = System::Drawing::Point(433, 118);
			this->buttonRechercher->Name = L"buttonRechercher";
			this->buttonRechercher->Size = System::Drawing::Size(105, 39);
			this->buttonRechercher->TabIndex = 5;
			this->buttonRechercher->Text = L"RECHERCHER";
			this->buttonRechercher->UseVisualStyleBackColor = true;
			this->buttonRechercher->Click += gcnew System::EventHandler(this, &Enregistrement::buttonRechercher_Click);
			// 
			// labelID
			// 
			this->labelID->AutoSize = true;
			this->labelID->Location = System::Drawing::Point(12, 397);
			this->labelID->Name = L"labelID";
			this->labelID->Size = System::Drawing::Size(18, 13);
			this->labelID->TabIndex = 11;
			this->labelID->Text = L"id:";
			// 
			// textBoxID
			// 
			this->textBoxID->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 12, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->textBoxID->Location = System::Drawing::Point(36, 389);
			this->textBoxID->Name = L"textBoxID";
			this->textBoxID->Size = System::Drawing::Size(48, 26);
			this->textBoxID->TabIndex = 9;
			this->textBoxID->TextAlign = System::Windows::Forms::HorizontalAlignment::Center;
			// 
			// buttonModifier
			// 
			this->buttonModifier->Cursor = System::Windows::Forms::Cursors::Hand;
			this->buttonModifier->Location = System::Drawing::Point(85, 144);
			this->buttonModifier->Name = L"buttonModifier";
			this->buttonModifier->Size = System::Drawing::Size(105, 39);
			this->buttonModifier->TabIndex = 12;
			this->buttonModifier->Text = L"MODIFIER";
			this->buttonModifier->UseVisualStyleBackColor = true;
			this->buttonModifier->Click += gcnew System::EventHandler(this, &Enregistrement::buttonModifier_Click);
			// 
			// dataGridView1
			// 
			this->dataGridView1->AllowUserToAddRows = false;
			this->dataGridView1->AllowUserToDeleteRows = false;
			this->dataGridView1->ColumnHeadersHeightSizeMode = System::Windows::Forms::DataGridViewColumnHeadersHeightSizeMode::AutoSize;
			this->dataGridView1->Columns->AddRange(gcnew cli::array< System::Windows::Forms::DataGridViewColumn^  >(6) {
				this->datagrid_id,
					this->datagrid_NomREUR, this->datagrid_PrenomREUR, this->datagrid_numSARD, this->datagrid_numRFID, this->datagrid_NomURSE
			});
			dataGridViewCellStyle1->Alignment = System::Windows::Forms::DataGridViewContentAlignment::MiddleLeft;
			dataGridViewCellStyle1->BackColor = System::Drawing::SystemColors::Window;
			dataGridViewCellStyle1->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 11.25F, System::Drawing::FontStyle::Regular,
				System::Drawing::GraphicsUnit::Point, static_cast<System::Byte>(0)));
			dataGridViewCellStyle1->ForeColor = System::Drawing::SystemColors::ControlText;
			dataGridViewCellStyle1->SelectionBackColor = System::Drawing::SystemColors::Highlight;
			dataGridViewCellStyle1->SelectionForeColor = System::Drawing::SystemColors::HighlightText;
			dataGridViewCellStyle1->WrapMode = System::Windows::Forms::DataGridViewTriState::False;
			this->dataGridView1->DefaultCellStyle = dataGridViewCellStyle1;
			this->dataGridView1->Location = System::Drawing::Point(114, 34);
			this->dataGridView1->Name = L"dataGridView1";
			this->dataGridView1->ReadOnly = true;
			this->dataGridView1->ScrollBars = System::Windows::Forms::ScrollBars::Vertical;
			this->dataGridView1->Size = System::Drawing::Size(712, 318);
			this->dataGridView1->TabIndex = 20;
			// 
			// datagrid_id
			// 
			this->datagrid_id->HeaderText = L"ID";
			this->datagrid_id->Name = L"datagrid_id";
			this->datagrid_id->ReadOnly = true;
			this->datagrid_id->Width = 50;
			// 
			// datagrid_NomREUR
			// 
			this->datagrid_NomREUR->HeaderText = L"Nom";
			this->datagrid_NomREUR->Name = L"datagrid_NomREUR";
			this->datagrid_NomREUR->ReadOnly = true;
			// 
			// datagrid_PrenomREUR
			// 
			this->datagrid_PrenomREUR->HeaderText = L"Prenom";
			this->datagrid_PrenomREUR->Name = L"datagrid_PrenomREUR";
			this->datagrid_PrenomREUR->ReadOnly = true;
			// 
			// datagrid_numSARD
			// 
			this->datagrid_numSARD->HeaderText = L"N°Dossard";
			this->datagrid_numSARD->Name = L"datagrid_numSARD";
			this->datagrid_numSARD->ReadOnly = true;
			this->datagrid_numSARD->Width = 120;
			// 
			// datagrid_numRFID
			// 
			this->datagrid_numRFID->HeaderText = L"N°RFID";
			this->datagrid_numRFID->Name = L"datagrid_numRFID";
			this->datagrid_numRFID->ReadOnly = true;
			this->datagrid_numRFID->Width = 120;
			// 
			// datagrid_NomURSE
			// 
			this->datagrid_NomURSE->HeaderText = L"Nom de la Course";
			this->datagrid_NomURSE->Name = L"datagrid_NomURSE";
			this->datagrid_NomURSE->ReadOnly = true;
			this->datagrid_NomURSE->Width = 180;
			// 
			// groupBox2
			// 
			this->groupBox2->Controls->Add(this->nbrAssociation);
			this->groupBox2->Controls->Add(this->dataGridView1);
			this->groupBox2->ForeColor = System::Drawing::SystemColors::ControlText;
			this->groupBox2->Location = System::Drawing::Point(327, 302);
			this->groupBox2->Name = L"groupBox2";
			this->groupBox2->Size = System::Drawing::Size(915, 377);
			this->groupBox2->TabIndex = 15;
			this->groupBox2->TabStop = false;
			this->groupBox2->Text = L"Enregistrement Réussi";
			// 
			// nbrAssociation
			// 
			this->nbrAssociation->BackColor = System::Drawing::SystemColors::InactiveCaption;
			this->nbrAssociation->BorderStyle = System::Windows::Forms::BorderStyle::FixedSingle;
			this->nbrAssociation->Cursor = System::Windows::Forms::Cursors::Arrow;
			this->nbrAssociation->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 14.25F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->nbrAssociation->ForeColor = System::Drawing::SystemColors::ActiveCaptionText;
			this->nbrAssociation->Location = System::Drawing::Point(31, 34);
			this->nbrAssociation->Multiline = true;
			this->nbrAssociation->Name = L"nbrAssociation";
			this->nbrAssociation->ReadOnly = true;
			this->nbrAssociation->Size = System::Drawing::Size(55, 29);
			this->nbrAssociation->TabIndex = 27;
			this->nbrAssociation->Text = L"0";
			this->nbrAssociation->TextAlign = System::Windows::Forms::HorizontalAlignment::Center;
			// 
			// label1
			// 
			this->label1->AutoSize = true;
			this->label1->ForeColor = System::Drawing::Color::Red;
			this->label1->Location = System::Drawing::Point(33, 259);
			this->label1->Name = L"label1";
			this->label1->Size = System::Drawing::Size(230, 13);
			this->label1->TabIndex = 9;
			this->label1->Text = L"Note : Seul le numéro dossard peut être modifié";
			// 
			// textBoxModifDossard
			// 
			this->textBoxModifDossard->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 12, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->textBoxModifDossard->Location = System::Drawing::Point(90, 389);
			this->textBoxModifDossard->Name = L"textBoxModifDossard";
			this->textBoxModifDossard->Size = System::Drawing::Size(204, 26);
			this->textBoxModifDossard->TabIndex = 11;
			this->textBoxModifDossard->TextAlign = System::Windows::Forms::HorizontalAlignment::Center;
			// 
			// labelModifDossard
			// 
			this->labelModifDossard->AutoSize = true;
			this->labelModifDossard->Location = System::Drawing::Point(131, 45);
			this->labelModifDossard->Name = L"labelModifDossard";
			this->labelModifDossard->Size = System::Drawing::Size(105, 13);
			this->labelModifDossard->TabIndex = 12;
			this->labelModifDossard->Text = L"Nouveau N°Dossard";
			// 
			// groupBox3
			// 
			this->groupBox3->Controls->Add(this->buttonModifier);
			this->groupBox3->Controls->Add(this->labelModifDossard);
			this->groupBox3->Location = System::Drawing::Point(5, 302);
			this->groupBox3->Name = L"groupBox3";
			this->groupBox3->Size = System::Drawing::Size(301, 230);
			this->groupBox3->TabIndex = 13;
			this->groupBox3->TabStop = false;
			this->groupBox3->Text = L"Modification n°Dossard";
			// 
			// pictureBoxNoConnect
			// 
			this->pictureBoxNoConnect->Image = (cli::safe_cast<System::Drawing::Image^>(resources->GetObject(L"pictureBoxNoConnect.Image")));
			this->pictureBoxNoConnect->Location = System::Drawing::Point(543, 251);
			this->pictureBoxNoConnect->Name = L"pictureBoxNoConnect";
			this->pictureBoxNoConnect->Size = System::Drawing::Size(458, 35);
			this->pictureBoxNoConnect->TabIndex = 14;
			this->pictureBoxNoConnect->TabStop = false;
			this->pictureBoxNoConnect->Visible = false;
			// 
			// labelHeure
			// 
			this->labelHeure->AutoSize = true;
			this->labelHeure->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 20.25F, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->labelHeure->Location = System::Drawing::Point(84, 26);
			this->labelHeure->Name = L"labelHeure";
			this->labelHeure->Size = System::Drawing::Size(82, 31);
			this->labelHeure->TabIndex = 16;
			this->labelHeure->Text = L"00:00";
			// 
			// timerHeure
			// 
			this->timerHeure->Enabled = true;
			this->timerHeure->Interval = 1000;
			this->timerHeure->Tick += gcnew System::EventHandler(this, &Enregistrement::timerHeure_Tick);
			// 
			// buttonDirect
			// 
			this->buttonDirect->Cursor = System::Windows::Forms::Cursors::Hand;
			this->buttonDirect->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 9.75F, System::Drawing::FontStyle::Bold, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->buttonDirect->Location = System::Drawing::Point(707, 240);
			this->buttonDirect->Name = L"buttonDirect";
			this->buttonDirect->Size = System::Drawing::Size(150, 51);
			this->buttonDirect->TabIndex = 19;
			this->buttonDirect->Text = L"Course en Direct";
			this->buttonDirect->UseVisualStyleBackColor = true;
			this->buttonDirect->Click += gcnew System::EventHandler(this, &Enregistrement::buttonDirect_Click);
			// 
			// comboBox_Port
			// 
			this->comboBox_Port->FormattingEnabled = true;
			this->comboBox_Port->Location = System::Drawing::Point(95, 100);
			this->comboBox_Port->Name = L"comboBox_Port";
			this->comboBox_Port->Size = System::Drawing::Size(100, 21);
			this->comboBox_Port->TabIndex = 21;
			// 
			// label_Port
			// 
			this->label_Port->AutoSize = true;
			this->label_Port->Font = (gcnew System::Drawing::Font(L"Microsoft Sans Serif", 9, System::Drawing::FontStyle::Regular, System::Drawing::GraphicsUnit::Point,
				static_cast<System::Byte>(0)));
			this->label_Port->Location = System::Drawing::Point(49, 101);
			this->label_Port->Name = L"label_Port";
			this->label_Port->Size = System::Drawing::Size(35, 15);
			this->label_Port->TabIndex = 22;
			this->label_Port->Text = L"Port :";
			// 
			// button_Ouvrir
			// 
			this->button_Ouvrir->BackColor = System::Drawing::Color::Transparent;
			this->button_Ouvrir->Cursor = System::Windows::Forms::Cursors::Hand;
			this->button_Ouvrir->ForeColor = System::Drawing::SystemColors::ControlText;
			this->button_Ouvrir->Location = System::Drawing::Point(33, 69);
			this->button_Ouvrir->Name = L"button_Ouvrir";
			this->button_Ouvrir->Size = System::Drawing::Size(63, 23);
			this->button_Ouvrir->TabIndex = 23;
			this->button_Ouvrir->Text = L"Ouvrir";
			this->button_Ouvrir->UseVisualStyleBackColor = false;
			this->button_Ouvrir->Click += gcnew System::EventHandler(this, &Enregistrement::button_Ouvrir_Click);
			// 
			// groupBox_port
			// 
			this->groupBox_port->Controls->Add(this->button_Fermer);
			this->groupBox_port->Controls->Add(this->button_Ouvrir);
			this->groupBox_port->Location = System::Drawing::Point(36, 71);
			this->groupBox_port->Name = L"groupBox_port";
			this->groupBox_port->Size = System::Drawing::Size(227, 144);
			this->groupBox_port->TabIndex = 25;
			this->groupBox_port->TabStop = false;
			this->groupBox_port->Text = L"Connexion au lecteur RFID";
			// 
			// button_Fermer
			// 
			this->button_Fermer->BackColor = System::Drawing::Color::Transparent;
			this->button_Fermer->Cursor = System::Windows::Forms::Cursors::No;
			this->button_Fermer->ForeColor = System::Drawing::Color::Silver;
			this->button_Fermer->Location = System::Drawing::Point(112, 69);
			this->button_Fermer->Name = L"button_Fermer";
			this->button_Fermer->Size = System::Drawing::Size(63, 23);
			this->button_Fermer->TabIndex = 24;
			this->button_Fermer->Text = L"Fermer";
			this->button_Fermer->UseVisualStyleBackColor = false;
			this->button_Fermer->Click += gcnew System::EventHandler(this, &Enregistrement::button_Fermer_Click);
			// 
			// Enregistrement
			// 
			this->AutoScaleDimensions = System::Drawing::SizeF(6, 13);
			this->AutoScaleMode = System::Windows::Forms::AutoScaleMode::Font;
			this->BackColor = System::Drawing::SystemColors::ActiveCaption;
			this->ClientSize = System::Drawing::Size(1370, 749);
			this->Controls->Add(this->label_Port);
			this->Controls->Add(this->comboBox_Port);
			this->Controls->Add(this->buttonDirect);
			this->Controls->Add(this->labelHeure);
			this->Controls->Add(this->pictureBoxNoConnect);
			this->Controls->Add(this->labelID);
			this->Controls->Add(this->textBoxID);
			this->Controls->Add(this->textBoxModifDossard);
			this->Controls->Add(this->label1);
			this->Controls->Add(this->groupBox1);
			this->Controls->Add(this->groupBox2);
			this->Controls->Add(this->groupBox3);
			this->Controls->Add(this->groupBox_port);
			this->Icon = (cli::safe_cast<System::Drawing::Icon^>(resources->GetObject(L"$this.Icon")));
			this->Name = L"Enregistrement";
			this->StartPosition = System::Windows::Forms::FormStartPosition::CenterScreen;
			this->Text = L"Enregistrement";
			this->FormClosing += gcnew System::Windows::Forms::FormClosingEventHandler(this, &Enregistrement::MyForm_FormClosing);
			this->groupBox1->ResumeLayout(false);
			this->groupBox1->PerformLayout();
			(cli::safe_cast<System::ComponentModel::ISupportInitialize^>(this->dataGridView1))->EndInit();
			this->groupBox2->ResumeLayout(false);
			this->groupBox2->PerformLayout();
			this->groupBox3->ResumeLayout(false);
			this->groupBox3->PerformLayout();
			(cli::safe_cast<System::ComponentModel::ISupportInitialize^>(this->pictureBoxNoConnect))->EndInit();
			this->groupBox_port->ResumeLayout(false);
			this->ResumeLayout(false);
			this->PerformLayout();

		}
#pragma endregion
		
	void LireRFID()
	{
		String^ date = DateTime::Now.Today.ToString("d");
		//Inverser date
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1];
		//On vérifie si le coureur s'est bien inscrit à la course d'aujourd'hui, si oui récupérer idREUR, idURSE dans la base de donnée
		String^ requete = "SELECT coureur.idREUR, coureur.idURSE FROM coureur INNER JOIN course ON coureur.idURSE=course.idURSE WHERE coureur.nomREUR='" + textBoxNomREUR->Text + "' AND coureur.prenomREUR='" + textBoxPrenomREUR->Text + "' AND course.dateURSE='" + dateInverser + "'";
		MySqlCommand^ commandRecup = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ reader = commandRecup->ExecuteReader();
		bool coureurBienInscrit = false;
		coureurBienInscrit = reader->Read();
		int idREUR;
		int idCourseParticipe;
		if (coureurBienInscrit) { idREUR = reader->GetInt16(0); idCourseParticipe = reader->GetInt16(1); }
		reader->Close(); //Fermer le reader sinon risque de pertuber les autres reader après

		// On vérifie si le coureur n'a pas déja été enregistré
		requete = "SELECT numSARD FROM dossard where idREUR=" + idREUR;
		MySqlCommand^ commandVerifEnregistre = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ readerVerifEnregistre = commandVerifEnregistre->ExecuteReader();
		bool coureurEnregistre = false;
		coureurEnregistre = readerVerifEnregistre->Read();
		readerVerifEnregistre->Close();

		// On vérifie si il y a une course aujourd'hui
		requete = "SELECT idURSE FROM course where dateURSE='" + dateInverser + "'";
		MySqlCommand^ commandVerifCourseAujourdhui = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ readerVerifCourse = commandVerifCourseAujourdhui->ExecuteReader();
		bool okCourse = readerVerifCourse->Read();
		readerVerifCourse->Close();

		if (textBoxRFID->Text == "Attente badge..." && textBoxNomREUR->Text != "" && textBoxPrenomREUR->Text != "" && textBoxSARD->Text != "")
		{
			if (coureurBienInscrit && !coureurEnregistre)
			{
				String^ numRFID = _serialPortRFID->ReadLine();
				numRFID = _serialPortRFID->ReadLine();  // On est obliger de lire une 2ème fois car sinon retourne vide (bug)
				textBoxRFID->Text = numRFID;
				Associer(idREUR, idCourseParticipe);
			}
			else if (!okCourse) // Si pas de course on affiche un message indiquant qu'il n'y a pas de course organisé aujourd'hui
			{
				MessageBox::Show("Aucune course organisé aujourd'hui"); 
			}
			else if (!coureurBienInscrit)
			{
				MessageBox::Show("Coureur non inscrit");
			}
			else
			{
				MessageBox::Show("Coureur déja enregistré");
			}
		}
	}

	void Associer(int idREUR, int idURSE)
	{
								
			String^ numRFID;
			for (int i=0; i < textBoxRFID->Text->Length; i++) 
			{
				if (i < 10)
				{
					numRFID += textBoxRFID->Text[i]; // Récupérer que les 10 premiers caractères du n°RFID dans la textBoxRFID
				}
			}

			// Préparation de la requête
			String^ requete = "INSERT INTO dossard (idREUR,idURSE,numSARD,numRFID) VALUES ('" + idREUR + "','" + idURSE + "','" + textBoxSARD->Text + "','" + numRFID + "')";
			MySqlCommand^ commandInsert = gcnew MySqlCommand(requete, _conn);

				commandInsert->ExecuteNonQuery();  // Exécution de la requête
				textBoxNomREUR->Text = "";
				textBoxPrenomREUR->Text = "";
				int numSARD = Convert::ToInt32(textBoxSARD->Text);
				numSARD++; // incrémentation du numéro dossard
				textBoxSARD->Text = numSARD.ToString();
				textBoxRFID->Text = "Attente badge...";
				dataGridView1->Rows->Clear(); // efface le tableau
				AfficherTout(); // Mise à jour du tableau des associations RFID
				nbrAssociation->Text = dataGridView1->RowCount.ToString(); // affiche sur label_nbrAssociation le nombre d'association RFID
				dataGridView1->Rows[0]->DefaultCellStyle->BackColor = Color::Yellow; // couleur de fond jaune pour le nouveau enregistrement
				SuggestionNom(); // actualise le tableau des suggestions de nom de coureurs
				textBoxNomREUR->Focus(); // ramène focus sur textBoxNom
	}

	void AfficherTout()
	{
		String^ date = DateTime::Now.Today.ToString("d");
		//Inverser date
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1];
		// On récupère les 10 derniers enregistrements tout les 3 secondes(timer)
		String^ requeteRecupTout = "SELECT dossard.idSARD, coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, dossard.numRFID, course.nomURSE FROM ((dossard INNER JOIN coureur ON dossard.idREUR = coureur.idREUR) INNER JOIN course ON dossard.idURSE = course.idURSE) WHERE course.dateURSE='" + dateInverser + "' ORDER BY dossard.idSARD DESC";
		MySqlCommand^ command = gcnew MySqlCommand(requeteRecupTout, _conn);

		try
		{
			MySqlDataReader^ reader = command->ExecuteReader();
			while (reader->Read())
			{
				dataGridView1->Rows->Add(reader->GetString(0), reader->GetString(1), reader->GetString(2), reader->GetString(3), reader->GetString(4), reader->GetString(5));
			}
			reader->Close();
		}
		catch (Exception^ ex)
		{
			MessageBox::Show(ex->ToString());
		}	
	}

	void Modifier()
	{
		String^ date = DateTime::Now.Today.ToString("d");
		//Inverser date
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1];
		
		// On vérifie si id existe
		String^ requeteVerif = "SELECT idSARD, idREUR FROM (dossard INNER JOIN course ON dossard.idURSE=course.idURSE) WHERE dossard.idSARD='" + textBoxID->Text + "' AND course.dateURSE='" + dateInverser + "'";
		MySqlCommand^ commandVerif = gcnew MySqlCommand(requeteVerif, _conn);
		bool ok = false;
		MySqlDataReader^ reader = commandVerif->ExecuteReader();
		
		ok = reader->Read();
		int idREUR;
		if (ok)
		{
			idREUR = reader->GetInt16(1);
		}
		reader->Close();

		// On modifie si c'est bon
		if (ok)
		{
			String^ requeteModif = "UPDATE dossard SET numSARD='" + textBoxModifDossard->Text + "' WHERE idSARD='" + textBoxID->Text + "'";
			MySqlCommand^ commandModif = gcnew MySqlCommand(requeteModif, _conn);
			commandModif->ExecuteNonQuery();
			MessageBox::Show("Modification pris en compte !");
			// On actualise
			String^ requeteActualiser = "SELECT dossard.idSARD, coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, dossard.numRFID, course.nomURSE FROM ((dossard INNER JOIN coureur ON dossard.idREUR='" + idREUR + "' AND coureur.idREUR='" + idREUR + "') INNER JOIN course ON course.idURSE=dossard.idURSE)";
			MySqlCommand^ commandActualiser = gcnew MySqlCommand(requeteActualiser, _conn);
			MySqlDataReader^ readerActualiser = commandActualiser->ExecuteReader();
			readerActualiser->Read();
			dataGridView1->Rows->Clear(); // On netoie
			dataGridView1->Rows->Add(readerActualiser->GetString(0), readerActualiser->GetString(1), readerActualiser->GetString(2), readerActualiser->GetString(3), readerActualiser->GetString(4), readerActualiser->GetString(5));
			readerActualiser->Close(); // On ferme le DataReader sinon empêche un autre DataReader de faire son travail
			textBoxModifDossard->Text = "";
			textBoxID->Text = "";
		}
		// Sinon on afiche un message d'erreur
		else
		{
			MessageBox::Show("ID n'existe pas !");
		}
		
	}

	void RechercherAvecNomPrenom()
	{
		String^ date = DateTime::Now.Today.ToString("d");
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1]; //Inverser date
		String^ requete = "SELECT dossard.idSARD, coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, dossard.numRFID, course.nomURSE FROM course, dossard INNER JOIN coureur ON dossard.idREUR=coureur.idREUR WHERE coureur.nomREUR='" + textBoxNomREUR->Text + "' AND coureur.prenomREUR='" + textBoxPrenomREUR->Text + "'AND course.dateURSE='" + dateInverser + "'";
		MySqlCommand^ command = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ reader = command->ExecuteReader();
		if (reader->Read())
			{
				dataGridView1->Rows->Add(reader->GetString(0), reader->GetString(1), reader->GetString(2), reader->GetString(3), reader->GetString(4), reader->GetString(5));
				textBoxNomREUR->Text = "";
				textBoxPrenomREUR->Text = "";
			}
			reader->Close();
	}

	void RechercherAvecNom()
	{
		String^ date = DateTime::Now.Today.ToString("d");
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1]; //Inverser date
		String^ requete = "SELECT dossard.idSARD, coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, dossard.numRFID, course.nomURSE FROM ((coureur INNER JOIN dossard ON coureur.idREUR=dossard.idREUR)INNER JOIN course ON coureur.idURSE=course.idURSE) WHERE coureur.nomREUR='" + textBoxNomREUR->Text + "' AND course.dateURSE='" + dateInverser + "'";
		MySqlCommand^ command = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ reader = command->ExecuteReader();
		
		while (reader->Read())
		{
			dataGridView1->Rows->Add(reader->GetString(0), reader->GetString(1), reader->GetString(2), reader->GetString(3), reader->GetString(4), reader->GetString(5));
			textBoxNomREUR->Text = "";
		}
		reader->Close();
	}

	void RechercherAvecPrenom()
	{
		String^ date = DateTime::Now.Today.ToString("d");
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1]; //Inverser date
		String^ requete = "SELECT dossard.idSARD, coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, dossard.numRFID, course.nomURSE FROM ((coureur INNER JOIN dossard ON coureur.idREUR=dossard.idREUR)INNER JOIN course ON coureur.idURSE=course.idURSE) WHERE coureur.prenomREUR='" + textBoxPrenomREUR->Text + "'AND course.dateURSE='" + dateInverser + "'";
		MySqlCommand^ command = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ reader = command->ExecuteReader();
	
		while (reader->Read())
		{
			dataGridView1->Rows->Add(reader->GetString(0), reader->GetString(1), reader->GetString(2), reader->GetString(3), reader->GetString(4), reader->GetString(5));
			textBoxPrenomREUR->Text = "";
		}
		reader->Close();
	}

	void RechercherAvecDossard()
	{
		String^ date = DateTime::Now.Today.ToString("d");
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1]; //Inverser date
		String^ requete = "SELECT dossard.idSARD, coureur.nomREUR, coureur.prenomREUR, dossard.numSARD, dossard.numRFID, course.nomURSE FROM course, dossard INNER JOIN coureur ON dossard.idREUR=coureur.idREUR WHERE dossard.numSARD='" + textBoxSARD->Text + "' AND course.dateURSE='" + dateInverser + "'";
		MySqlCommand^ command = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ reader = command->ExecuteReader();
		if (reader->Read())
		{
			dataGridView1->Rows->Add(reader->GetString(0), reader->GetString(1), reader->GetString(2), reader->GetString(3), reader->GetString(4), reader->GetString(5));
			textBoxSARD->Text = "";
		}
		reader->Close();
	}

	void SuggestionNom()
	{
		if (textBoxNomREUR->AutoCompleteCustomSource->Count > 0)
			textBoxNomREUR->AutoCompleteCustomSource->Clear();
		String^ date = DateTime::Now.Today.ToString("d");
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1]; //Inverser date
		String^ requete = "SELECT DISTINCT nomREUR FROM (coureur INNER JOIN course ON coureur.idURSE=course.idURSE) WHERE dateURSE='" + dateInverser + "' AND coureur.idREUR NOT IN (SELECT idREUR FROM dossard)";
		MySqlCommand^ command = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ reader = command->ExecuteReader();
		while (reader->Read())
		{
			textBoxNomREUR->AutoCompleteCustomSource->Add(reader->GetString(0));
		}
		reader->Close();
	}

	void SuggestionPrenom()
	{
		if (textBoxPrenomREUR->AutoCompleteCustomSource->Count > 0) // si tableau contient des prénoms
			textBoxPrenomREUR->AutoCompleteCustomSource->Clear(); // alors supprimer pour remplir après des nouveaux
		String^ date = DateTime::Now.Today.ToString("d");
		String^ dateInverser = date[6].ToString() + date[7] + date[8] + date[9] + date[5] + date[3] + date[4] + date[2] + date[0] + date[1]; //Inverser date
		// Requête pour récupérer tous les prénoms qui correspondent au nom dans la textBoxNom et qui n'ont pas encore été associés
		String^ requete = "SELECT prenomREUR FROM (coureur INNER JOIN course ON coureur.idURSE=course.idURSE) WHERE nomREUR='" + textBoxNomREUR->Text + "' AND dateURSE='" + dateInverser + "' AND coureur.idREUR NOT IN (SELECT idREUR FROM dossard)";
		MySqlCommand^ command = gcnew MySqlCommand(requete, _conn);
		MySqlDataReader^ reader = command->ExecuteReader();
		while (reader->Read())
		{
			textBoxPrenomREUR->AutoCompleteCustomSource->Add(reader->GetString(0)); // on remplit le tableau avec les nouveaux prénoms
		}
		reader->Close();
		if (textBoxPrenomREUR->AutoCompleteCustomSource->Count == 1) // si MySql renvoie un seul prénom
			textBoxPrenomREUR->Text = textBoxPrenomREUR->AutoCompleteCustomSource[0]; // alors on l'affiche directement sur la textBoxPrenomREUR
	}




/***************************************** EVENEMENTS *************************************************************************/


/*---- Evènement qui se déclenche quand des données ont été reçues via le port COM connecté ---------*/
private: System::Void _serialPortRFID_DataReceived(System::Object^  sender, System::IO::Ports::SerialDataReceivedEventArgs^  e) {
	if (_connecteBddReussi)
	{
		Invoke(_monDelegateRFID); // LireRFID()
	}
	else
	{
		MessageBox::Show("Vérifier votre connexion internet");
	}
	// On ferme connexion port puis on l'ouvre encore pour effacer ce qui est dans la mémoire tampon
	_serialPortRFID->Close();
	_serialPortRFID->Open();
}

private: System::Void buttonModifier_Click(System::Object^  sender, System::EventArgs^  e) {
	if (_connecteBddReussi)
	{
		if (dataGridView1->RowCount > 0)
			if (textBoxModifDossard->Text != "" && textBoxID->Text != "") { Modifier(); }
			else if (textBoxModifDossard->Text == "" && textBoxID->Text == "") { MessageBox::Show("Veuillez indiquer un n°ID et le nouveau n°dossard"); }
			else if (textBoxID->Text == "") { MessageBox::Show("Veuillez indiquer un n°ID"); }
			else { MessageBox::Show("Veuillez indiquer le nouveau n°dossard"); }
		else
			MessageBox::Show("Aucun coureur enregistré");
	}
	else
	{
		MessageBox::Show("Vérifier votre connexion internet");
	}
}

private: System::Void buttonRechercher_Click(System::Object^  sender, System::EventArgs^  e) {
	if (_connecteBddReussi)
	{
		dataGridView1->Rows->Clear();

		if (textBoxNomREUR->Text != "" && textBoxPrenomREUR->Text != "")
		{
			RechercherAvecNomPrenom();
		}
		
		else if (textBoxNomREUR->Text != "")
		{
			RechercherAvecNom();
		}

		else if (textBoxPrenomREUR->Text != "")
		{
			RechercherAvecPrenom();
		}
		
		else if (textBoxSARD->Text != "")
		{
			RechercherAvecDossard();
		}
		else
		{
			AfficherTout();
		}
	}
	else
	{
		MessageBox::Show("Vérifier votre connexion internet");
	}
}

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

	labelHeure->Text = heure;
}

		 
private: System::Void buttonDirect_Click(System::Object^  sender, System::EventArgs^  e) {
	if (_connecteBddReussi)
	{
		Direct^ direct = gcnew Direct(_conn); // On passe comme argument la connexion à la base de données pour que la fenêtre direct la récupère et puisse se connecter aussi
		direct->Show();
	}
}

/*-----------------# REMETS EN BLANC LE NOUVEAU ENREGISTREMENT #-----------------*/
private: System::Void textBoxNomREUR_TextChanged(System::Object^  sender, System::EventArgs^  e) {
	if (dataGridView1->RowCount > 0) // si le tableau des associations RFID contient au moins une ligne, pour éviter erreur d'indexe du datagrid
		dataGridView1->Rows[0]->DefaultCellStyle->BackColor = Color::White; // remets couleur de fonds du nouveau enregistrement à blanc quand on écrit un nouveau nom
}

/*-----------------# OUVERTURE CONNEXION AU PORT #------------------*/
private: System::Void button_Ouvrir_Click(System::Object^  sender, System::EventArgs^  e) {
	if (comboBox_Port->Text != "" && !_lecteurBadgeConnecter)
	{
		_serialPortRFID = gcnew SerialPort();
		_serialPortRFID->PortName = comboBox_Port->Text;
		_serialPortRFID->BaudRate = 2400; // vitesse de transmission
		_serialPortRFID->DataBits = 8;
		try
		{
			_serialPortRFID->Open();  // Connexion au Port
			this->_serialPortRFID->DataReceived += gcnew System::IO::Ports::SerialDataReceivedEventHandler(this, &Enregistrement::_serialPortRFID_DataReceived);
			button_Ouvrir->ForeColor = Color::Silver;
			button_Fermer->ForeColor = Color::Black;
			button_Ouvrir->Cursor = Cursors::No;
			button_Fermer->Cursor = Cursors::Hand;
			_lecteurBadgeConnecter = true;
			textBoxRFID->Text = "Attente badge...";
		}
		catch (Exception^ ex)
		{
			_lecteurBadgeConnecter = false;
		}
	}
}

/*-------------------# FERMETURE CONNEXION AU PORT #------------------*/
private: System::Void button_Fermer_Click(System::Object^  sender, System::EventArgs^  e) {
	if (_lecteurBadgeConnecter)
	{
		_serialPortRFID->Close();
		comboBox_Port->Text = "";
		button_Fermer->ForeColor = Color::Silver;
		button_Ouvrir->ForeColor = Color::Black;
		button_Ouvrir->Cursor = Cursors::Hand;
		button_Fermer->Cursor = Cursors::No;
		_lecteurBadgeConnecter = false;
		textBoxRFID->Text = "";
	}
}

/*-----------# SUGGESTION DE PRENOMS DE COUREURS INSCRIT A LA COURSE #--------------*/
// quand textboxPrenom a le controle
private: System::Void textBoxPrenomREUR_Enter(System::Object^  sender, System::EventArgs^  e) {
	if (_connecteBddReussi && textBoxPrenomREUR->Text == "")
		SuggestionPrenom();
}

/*----------------------# À LA FERMETURE DE L'IHM #-------------------------*/
private: System::Void MyForm_FormClosing(System::Object^  sender, System::Windows::Forms::FormClosingEventArgs^  e) {
	if (_serialPortRFID != nullptr && _serialPortRFID->IsOpen)
		_serialPortRFID->Close(); //Fermeture connexion au Port
	_conn->Close(); //Fermeture connexion base de donnée 
}

};
}
