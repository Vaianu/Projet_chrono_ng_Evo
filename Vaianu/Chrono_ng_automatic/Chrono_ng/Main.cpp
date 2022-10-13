#include "Enregistrement.h"

using namespace System;
using namespace System::Windows::Forms;


[STAThread]
void Main(array<String^>^ args)
{
	Application::EnableVisualStyles();
	Application::SetCompatibleTextRenderingDefault(false);

	Chrono_ng::Enregistrement form;
	Application::Run(%form);
}
