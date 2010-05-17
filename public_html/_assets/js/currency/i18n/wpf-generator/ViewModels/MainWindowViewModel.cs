using System.Windows.Controls;
using wpf_generator.Models;

namespace wpf_generator.ViewModels
{
    public class MainWindowViewModel
    {
        public MainWindowModel Model { get; private set; }

        public MainWindowViewModel()
        {
            Model = new MainWindowModel();

            var generatorItem = new TabItem();
            generatorItem.IsSelected = true;
            generatorItem.Header = "Generator";
            generatorItem.Content = new GeneratorViewModel();
            Model.SelectedTab = generatorItem;
            Model.TabViews.Add(generatorItem);

            var mergeItem = new TabItem();
            mergeItem.Header = "Merge";
            mergeItem.Content = new MergeViewModel();
            Model.TabViews.Add(mergeItem);

            var licenseItem = new TabItem();
            licenseItem.Header = "License Info";
            licenseItem.Content = new LicenseViewModel();
            Model.TabViews.Add(licenseItem);
        }
    }
}
