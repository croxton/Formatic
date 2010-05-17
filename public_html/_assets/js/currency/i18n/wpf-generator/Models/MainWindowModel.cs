using System.Collections.ObjectModel;
using System.Windows;
using System.Windows.Controls;

namespace wpf_generator.Models
{
    public class MainWindowModel : DependencyObject
    {
        public MainWindowModel()
        {
            TabViews = new ObservableCollection<object>();
        }

        public ObservableCollection<object> TabViews
        {
            get { return (ObservableCollection<object>)GetValue(TabViewsProperty); }
            set { SetValue(TabViewsProperty, value); }
        }

        // Using a DependencyProperty as the backing store for TabViews.  This enables animation, styling, binding, etc...
        public static readonly DependencyProperty TabViewsProperty =
            DependencyProperty.Register("TabViews", typeof(ObservableCollection<object>), typeof(MainWindowModel), new UIPropertyMetadata(null));

        public TabItem SelectedTab { get; set; }
    }
}
