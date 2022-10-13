#pragma once

namespace Chrono_ng {

	using namespace System;
	using namespace System::ComponentModel;
	using namespace System::Collections;
	using namespace System::Windows::Forms;
	using namespace System::Data;
	using namespace System::Drawing;

	/// <summary>
	/// Description résumée de Authentification
	/// </summary>
	public ref class Authentification : public System::Windows::Forms::Form
	{
	public:
		Authentification(void)
		{
			InitializeComponent();
			//
			//TODO: ajoutez ici le code du constructeur
			//
		}

	protected:
		/// <summary>
		/// Nettoyage des ressources utilisées.
		/// </summary>
		~Authentification()
		{
			if (components)
			{
				delete components;
			}
		}
	private: System::Windows::Forms::TextBox^  textBox_utilisateur;
	private: System::Windows::Forms::TextBox^  textBox_mdp;
	private: System::Windows::Forms::Label^  labelIdentifiant;
	protected:

	protected:


	private: System::Windows::Forms::Label^  labelMdp;
	private: System::Windows::Forms::Button^  button1;

	protected:

	private:
		/// <summary>
		/// Variable nécessaire au concepteur.
		/// </summary>
		System::ComponentModel::Container ^components;

#pragma region Windows Form Designer generated code
		/// <summary>
		/// Méthode requise pour la prise en charge du concepteur - ne modifiez pas
		/// le contenu de cette méthode avec l'éditeur de code.
		/// </summary>
		void InitializeComponent(void)
		{
			this->textBox_utilisateur = (gcnew System::Windows::Forms::TextBox());
			this->textBox_mdp = (gcnew System::Windows::Forms::TextBox());
			this->labelIdentifiant = (gcnew System::Windows::Forms::Label());
			this->labelMdp = (gcnew System::Windows::Forms::Label());
			this->button1 = (gcnew System::Windows::Forms::Button());
			this->SuspendLayout();
			// 
			// textBox_utilisateur
			// 
			this->textBox_utilisateur->Location = System::Drawing::Point(104, 76);
			this->textBox_utilisateur->Name = L"textBox_utilisateur";
			this->textBox_utilisateur->Size = System::Drawing::Size(100, 20);
			this->textBox_utilisateur->TabIndex = 0;
			// 
			// textBox_mdp
			// 
			this->textBox_mdp->Location = System::Drawing::Point(104, 123);
			this->textBox_mdp->Name = L"textBox_mdp";
			this->textBox_mdp->Size = System::Drawing::Size(100, 20);
			this->textBox_mdp->TabIndex = 1;
			// 
			// labelIdentifiant
			// 
			this->labelIdentifiant->AutoSize = true;
			this->labelIdentifiant->Location = System::Drawing::Point(30, 79);
			this->labelIdentifiant->Name = L"labelIdentifiant";
			this->labelIdentifiant->Size = System::Drawing::Size(59, 13);
			this->labelIdentifiant->TabIndex = 7;
			this->labelIdentifiant->Text = L"Identifiant :";
			// 
			// labelMdp
			// 
			this->labelMdp->AutoSize = true;
			this->labelMdp->Location = System::Drawing::Point(12, 126);
			this->labelMdp->Name = L"labelMdp";
			this->labelMdp->Size = System::Drawing::Size(77, 13);
			this->labelMdp->TabIndex = 8;
			this->labelMdp->Text = L"Mot de passe :";
			// 
			// button1
			// 
			this->button1->Location = System::Drawing::Point(115, 164);
			this->button1->Name = L"button1";
			this->button1->Size = System::Drawing::Size(75, 23);
			this->button1->TabIndex = 2;
			this->button1->Text = L"connexion";
			this->button1->UseVisualStyleBackColor = true;
			this->button1->Click += gcnew System::EventHandler(this, &Authentification::button1_Click);
			// 
			// Authentification
			// 
			this->AutoScaleDimensions = System::Drawing::SizeF(6, 13);
			this->AutoScaleMode = System::Windows::Forms::AutoScaleMode::Font;
			this->ClientSize = System::Drawing::Size(273, 215);
			this->Controls->Add(this->button1);
			this->Controls->Add(this->labelMdp);
			this->Controls->Add(this->labelIdentifiant);
			this->Controls->Add(this->textBox_mdp);
			this->Controls->Add(this->textBox_utilisateur);
			this->Name = L"Authentification";
			this->StartPosition = System::Windows::Forms::FormStartPosition::CenterScreen;
			this->Text = L"Authentification";
			this->ResumeLayout(false);
			this->PerformLayout();

		}
#pragma endregion
	private: System::Void button1_Click(System::Object^  sender, System::EventArgs^  e) {
		if (textBox_utilisateur->Text == "a" && textBox_mdp->Text == "a")
		{
			this->Close();
		}
		else
		{
			MessageBox::Show("Utilisateur ou Mot de Passe incorrect");
		}
	}
};
}
