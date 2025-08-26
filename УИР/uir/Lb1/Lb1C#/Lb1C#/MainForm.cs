using System.Diagnostics;
using Lb1C_.List;
using Lb1C_.Tree;

namespace Lb1C_;

public partial class MainForm : Form
{
    private readonly LinkedList list = new();
    private Button gen60Btn, gen350Btn, gen1500Btn, gen3500Btn, gen8000Btn;
    private Label infoLabel;

    private TextBox inputBox;
    private ListView listView;
    private Button loadBtn, addBtn, deleteBtn, findBtn, clearBtn;
    private TextBox maxBox;
    private BinarySearchTree tree = new();
    private TreeView treeView;

    public MainForm()
    {
        InitializeComponent();
        InitControls();
    }

    private void InitControls()
    {
        inputBox = new TextBox { Left = 10, Top = 10, Width = 200 };
        maxBox = new TextBox { Left = 220, Top = 10, Width = 50, Text = "100" };
        loadBtn = new Button { Text = "Load", Left = 280, Top = 10 };
        addBtn = new Button { Text = "Add", Left = 10, Top = 40 };
        deleteBtn = new Button { Text = "Delete", Left = 80, Top = 40 };
        findBtn = new Button { Text = "Find", Left = 160, Top = 40 };
        clearBtn = new Button { Text = "Clear All", Left = 240, Top = 40 };

        gen60Btn = new Button { Text = "Gen 60", Left = 400, Top = 10 };
        gen350Btn = new Button { Text = "Gen 350", Left = 480, Top = 10 };
        gen1500Btn = new Button { Text = "Gen 1500", Left = 560, Top = 10 };
        gen3500Btn = new Button { Text = "Gen 3500", Left = 640, Top = 10 };
        gen8000Btn = new Button { Text = "Gen 8000", Left = 720, Top = 10 };

        listView = new ListView { Left = 10, Top = 80, Width = 300, Height = 300, View = View.List };
        treeView = new TreeView { Left = 320, Top = 80, Width = 300, Height = 300 };
        infoLabel = new Label { Left = 10, Top = 400, Width = 600, Height = 50 };

        Controls.AddRange(new Control[]
        {
            inputBox, maxBox, clearBtn, loadBtn, addBtn, deleteBtn, findBtn,
            gen60Btn, gen350Btn, gen1500Btn, gen3500Btn, gen8000Btn,
            listView, treeView, infoLabel
        });

        loadBtn.Click += LoadBtn_Click;
        addBtn.Click += AddBtn_Click;
        deleteBtn.Click += DeleteBtn_Click;
        findBtn.Click += FindBtn_Click;
        clearBtn.Click += ClearBtn_Click;

        gen60Btn.Click += (s, e) => GenerateData(60);
        gen350Btn.Click += (s, e) => GenerateData(350);
        gen1500Btn.Click += (s, e) => GenerateData(1500);
        gen3500Btn.Click += (s, e) => GenerateData(3500);
        gen8000Btn.Click += (s, e) => GenerateData(8000);
    }

    private void GenerateData(int count)
    {
        list.Clear();
        tree = new BinarySearchTree();
        var baseDate = DateTime.Today;

        var random = new Random();
        var times = Enumerable.Range(0, count)
            .Select(i => baseDate.AddMinutes(random.Next(0, 100000)).AddDays(random.Next(-15, 10)))
            .ToList();


        var sw = Stopwatch.StartNew();
        foreach (var time in times)
            list.Add(time);
        var listTime = sw.Elapsed;

        sw.Restart();
        foreach (var time in times)
            tree.Add(time);
        var treeTime = sw.Elapsed;

        infoLabel.Text =
            $"Generated {count}: List {listTime.TotalMilliseconds:F4}ms, Tree {treeTime.TotalMilliseconds:F4}ms";
        RefreshUI();
    }

    private void ClearBtn_Click(object sender, EventArgs e)
    {
        list.Clear();
        tree = new BinarySearchTree();
        RefreshUI();
        infoLabel.Text = "Cleared All";
    }

    private void LoadBtn_Click(object sender, EventArgs e)
    {
        var dialog = new OpenFileDialog();
        if (dialog.ShowDialog() != DialogResult.OK) return;

        list.Clear();
        tree = new BinarySearchTree();

        var max = int.TryParse(maxBox.Text, out var m) ? m : 100;

        var lines = File.ReadAllLines(dialog.FileName).Take(max);
        foreach (var line in lines)
        {
            var parts = line.Split('\t');
            if (DateTime.TryParse(parts[0], out var time))
            {
                list.Add(time);
                tree.Add(time);
            }
        }

        RefreshUI();
    }

    private void AddBtn_Click(object sender, EventArgs e)
    {
        if (!DateTime.TryParse(inputBox.Text, out var time)) return;

        var sw = Stopwatch.StartNew();
        list.Add(time);
        var t1 = sw.Elapsed;
        sw.Restart();
        tree.Add(time);
        var t2 = sw.Elapsed;

        infoLabel.Text = $"Added: List {t1.TotalMilliseconds:F4}ms, Tree {t2.TotalMilliseconds:F4}ms";
        RefreshUI();
    }

    private void DeleteBtn_Click(object sender, EventArgs e)
    {
        if (!DateTime.TryParse(inputBox.Text, out var time)) return;

        var sw = Stopwatch.StartNew();
        list.Delete(time);
        var t1 = sw.Elapsed;
        sw.Restart();
        tree.Delete(time);
        var t2 = sw.Elapsed;

        infoLabel.Text = $"Deleted: List {t1.TotalMilliseconds:F4}ms, Tree {t2.TotalMilliseconds:F4}ms";
        RefreshUI();
    }

    private void FindBtn_Click(object sender, EventArgs e)
    {
        if (!DateTime.TryParse(inputBox.Text, out var time)) return;

        var sw = Stopwatch.StartNew();
        var n1 = list.Find(time);
        var t1 = sw.Elapsed;
        sw.Restart();
        var n2 = tree.Find(time);
        var t2 = sw.Elapsed;

        infoLabel.Text =
            $"Found: List {t1.TotalMilliseconds:F4}ms ({n1 != null}), Tree {t2.TotalMilliseconds:F4}ms ({n2 != null})";
    }

    private void RefreshUI()
    {
        listView.Items.Clear();
        var node = list.Head;
        while (node != null)
        {
            listView.Items.Add(node.Time.ToString("G"));
            node = node.Next;
        }

        treeView.Nodes.Clear();
        DisplayTree(tree.Root, null, treeView);

        infoLabel.Text += $" | Tree: Count={tree.Count()}, Leaves={tree.Leaves()}, Depth={tree.Depth()}";
    }

    private void DisplayTree(BSTreeNode node, TreeNode parent, TreeView view)
    {
        if (node == null) return;

        var treeNode = new TreeNode(node.Time.ToString("G"));
        if (parent == null)
            view.Nodes.Add(treeNode);
        else
            parent.Nodes.Add(treeNode);

        DisplayTree(node.Left, treeNode, view);
        DisplayTree(node.Right, treeNode, view);
    }

    private void ListView_ItemActivate(object sender, EventArgs e)
    {
        if (listView.SelectedItems.Count > 0)
        {
            var selectedTime = listView.SelectedItems[0].Text;
            Clipboard.SetText(selectedTime);
        }
    }

    private void Form1_Load(object sender, EventArgs e)
    {
        listView.ItemActivate += ListView_ItemActivate;
    }
}