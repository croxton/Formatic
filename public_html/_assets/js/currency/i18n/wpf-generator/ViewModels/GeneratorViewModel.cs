using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.IO;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Input;
using wpf_generator.Helpers;
using wpf_generator.Models;
using forms = System.Windows.Forms;
using io = System.IO;

namespace wpf_generator.ViewModels
{
    public class GeneratorViewModel
    {
        public GeneratorModel Model { get; private set; }

        public GeneratorViewModel()
        {
            Model = new GeneratorModel();

            Model.BrowseCommand = new RelayCommand<object>(BrowseExecute);
            Model.RunCommand = new RelayCommand<object>(RunExecute, RunCanExecute);
            Model.SortCommand = new RelayCommand<string>(SortExecute);
            Model.ToListCommand = new RelayCommand<object>(ToListExecute, RunCanExecute);

#if DEBUG
            var path = "../../../";
#else
            var path = "../";
#endif

            var i18nFolder = new Uri(new Uri(AppDomain.CurrentDomain.BaseDirectory), path).LocalPath;
            Model.OutputFolder = i18nFolder;

            // wrapped cultures in a CollectionViewSource for sorting
            LoadCultures();
            Model.Cultures.Source = Model.ObsCultures;
            SortExecute("Code");
        }

        private void SortExecute(string propertyName)
        {
            if (Model.Cultures.SortDescriptions.Count == 0)
            {
                Model.Cultures.SortDescriptions.Add(new SortDescription(propertyName, ListSortDirection.Ascending));
                return;
            }

            var currentSort = Model.Cultures.SortDescriptions[0];
            var direction = ListSortDirection.Ascending;
            if (currentSort.PropertyName == propertyName)
            {
                direction = currentSort.Direction == ListSortDirection.Ascending
                                ? ListSortDirection.Descending
                                : ListSortDirection.Ascending;
            }

            Model.Cultures.SortDescriptions.Clear();
            Model.Cultures.SortDescriptions.Add(new SortDescription(propertyName, direction));
        }

        private void LoadCultures()
        {

            foreach (var ci in Helpers.Localization.Cultures)
                Model.ObsCultures.Add(ci);
        }

        private void RunExecute(object obj)
        {
            foreach(CountryInfo c in Model.Cultures.View)
            {
                if (!c.IsSelected) continue;

                string output = io::Path.Combine(Model.OutputFolder, "jquery.formatCurrency." + c.Code + ".js");
                using (var writer = new StreamWriter(output, false, Encoding.UTF8))
                {
                    if (Model.ShouldAppendLicense)
                        DocumentWriter.WriteLicense(writer);

                    DocumentWriter.WriteJQueryHeader(writer);

                    var ci = new System.Globalization.CultureInfo(c.Code);
                    writer.Write("\t$.formatCurrency.regions['{0}'] = ", c.Code);
                    writer.Write(Helpers.Localization.CurrencyFormat(ci.NumberFormat));
                    writer.WriteLine(";\n");

                    DocumentWriter.WriteJQueryFooter(writer);

                }
            }
            MessageBox.Show("Done");
            //LoadFiles();
        }

        private bool RunCanExecute(object obj)
        {
            return !string.IsNullOrEmpty(Model.OutputFolder);
        }

        private void BrowseExecute(object param)
        {
            var fd = new forms::FolderBrowserDialog();
            fd.SelectedPath = Model.OutputFolder;
            if (fd.ShowDialog() == forms::DialogResult.OK)
            {
                Model.OutputFolder = fd.SelectedPath;
            }
        }

        private void ToListExecute(object param)
        {
            var wiki = new StringBuilder();
            // Append Header
            wiki.AppendLine("= Supported Cultures =");
            wiki.AppendLine();
            wiki.AppendLine("|| *Code* || *Language* || *Sample* ||");

            foreach (var c in Model.ObsCultures)
            {
                if (!c.IsSelected) continue;

                // Append Item
                wiki.AppendFormat("|| *{0}* || {1} || {2} ||\n", c.Code, c.Country, c.Currency);
            }

            // Set it and inform user
            Clipboard.SetText(wiki.ToString());
            MessageBox.Show("The Wiki List is in your clipboard, go paste it.");
        }
    }
}
