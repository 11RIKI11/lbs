using System.Diagnostics;
using Lb1C_.AVLTree;
using Lb1C_.Tree;
using OfficeOpenXml;

namespace Lb1C_;

public class TreeToExcel
{
    private double Measure(Action action)
    {
        var sw = Stopwatch.StartNew();
        action();
        sw.Stop();
        return sw.Elapsed.TotalMilliseconds;
    }

    private string GetProjectDirectory()
    {
        var path = AppContext.BaseDirectory;
        while (!Directory.EnumerateFiles(path).Any(f => f.EndsWith(".csproj")))
            path = Directory.GetParent(path)!.FullName;
        return path;
    }

    public void RunBenchmarksAndExportToExcel()
    {
        var sizes = new[] { 40, 150, 1300, 3000, 8000 };
        var rand = new Random();

        ExcelPackage.License.SetNonCommercialOrganization("My Noncommercial organization");
        var filePath = Path.Combine(GetProjectDirectory(), "TreeBenchmark.xlsx");

        var data1 = Enumerable.Range(0, 1000)
            .Select(_ => rand.NextInt64(70000000000, 79999999999).ToString())
            .ToList();

        using (var package = new ExcelPackage())
        {
            var sheet = package.Workbook.Worksheets.Add("Benchmark");

            var row = 1;
            sheet.Cells[row, 1].Value = "Размер";
            sheet.Cells[row, 2].Value = "BST - Добавление";
            sheet.Cells[row, 3].Value = "BST - Удаление";
            sheet.Cells[row, 4].Value = "BST - Поиск";
            sheet.Cells[row, 5].Value = "AVL - Добавление";
            sheet.Cells[row, 6].Value = "AVL - Удаление";
            sheet.Cells[row, 7].Value = "AVL - Поиск";

            foreach (var size in sizes)
            {
                row++;
                sheet.Cells[row, 1].Value = size;

                double bstAddSum = 0, bstDeleteSum = 0, bstFindSum = 0;
                double avlAddSum = 0, avlDeleteSum = 0, avlFindSum = 0;

                for (var i = 1; i <= 5; i++)
                {
                    var data = Enumerable.Range(0, size)
                        .Select(_ => rand.NextInt64(70000000000, 79999999999).ToString())
                        .ToList();

                    var bst = new BinarySearchTree();
                    var avl = new AvlTree();

                    foreach (var phone in data)
                        bst.Add(phone);

                    foreach (var phone in data)
                        avl.Add(phone);

                    var newPhone = rand.NextInt64(70000000000, 79999999999).ToString();

                    var bstAdd = Measure(() => { bst.Add(newPhone); });

                    var avlAdd = Measure(() => { avl.Add(newPhone); });

                    var toDelete = data.OrderBy(_ => rand.Next()).First();

                    var bstDelete = Measure(() => bst.Delete(toDelete));
                    var avlDelete = Measure(() => avl.Delete(toDelete));

                    // Find по одному элементу
                    var remaining = data.Where(x => x != toDelete).ToList();
                    var toFind = remaining.OrderBy(_ => rand.Next()).First();

                    var bstFind = Measure(() => bst.Find(toFind));
                    var avlFind = Measure(() => avl.Find(toFind));

                    // Запись текущего прохода
                    row++;
                    sheet.Cells[row, 2].Value = bstAdd;
                    sheet.Cells[row, 3].Value = bstDelete;
                    sheet.Cells[row, 4].Value = bstFind;
                    sheet.Cells[row, 5].Value = avlAdd;
                    sheet.Cells[row, 6].Value = avlDelete;
                    sheet.Cells[row, 7].Value = avlFind;

                    bstAddSum += bstAdd;
                    bstDeleteSum += bstDelete;
                    bstFindSum += bstFind;
                    avlAddSum += avlAdd;
                    avlDeleteSum += avlDelete;
                    avlFindSum += avlFind;
                }

                // Средние значения
                row++;
                sheet.Cells[row, 1].Value = "Среднее";
                sheet.Cells[row, 2].Value = bstAddSum / 5;
                sheet.Cells[row, 3].Value = bstDeleteSum / 5;
                sheet.Cells[row, 4].Value = bstFindSum / 5;
                sheet.Cells[row, 5].Value = avlAddSum / 5;
                sheet.Cells[row, 6].Value = avlDeleteSum / 5;
                sheet.Cells[row, 7].Value = avlFindSum / 5;
            }

            sheet.Cells[sheet.Dimension.Address].AutoFitColumns();
            package.SaveAs(new FileInfo(filePath));
        }

        MessageBox.Show("Бенчмарк завершён. Результаты сохранены на рабочем столе в TreeBenchmark.xlsx");
    }
}