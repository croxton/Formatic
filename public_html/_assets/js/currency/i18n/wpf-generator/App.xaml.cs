using System.Windows;
using wpf_generator.ViewModels;

namespace wpf_generator
{
    /// <summary>
    /// Interaction logic for App.xaml
    /// </summary>
    public partial class App : Application
    {
        private void Application_Startup(object sender, StartupEventArgs e)
        {
            MainWindow = new Window1();
            MainWindow.DataContext = new MainWindowViewModel();
            MainWindow.Show();
        }
    }
}
