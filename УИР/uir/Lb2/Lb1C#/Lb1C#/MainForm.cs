using System.Diagnostics;
using Lb1C_.AVLTree;
using Lb1C_.Tree;

namespace Lb1C_;

public partial class MainForm : Form
{
    private AvlTree avlTreeHead = new();
    private TreeView avlTreeView;
    private TreeView bstTreeView;

    private Button gen50Btn, gen500Btn, gen1600Btn, gen6000Btn, gen9500Btn;

    private Label infoLabel;
    private TextBox inputBox;
    private Button loadBtn, addBtn, deleteBtn, findBtn, clearBtn, toExcelBtn;

    private BinarySearchTree treeHead = new();

    public MainForm()
    {
        InitializeComponent();
        InitControls();
    }

    private void InitControls()
    {
        inputBox = new TextBox { Left = 10, Top = 10, Width = 200 };
        loadBtn = new Button { Text = "Load", Left = 220, Top = 10, Height = 30 };
        addBtn = new Button { Text = "Add", Left = 10, Top = 40, Height = 30 };
        deleteBtn = new Button { Text = "Delete", Left = 80, Top = 40, Height = 30 };
        findBtn = new Button { Text = "Find", Left = 160, Top = 40, Height = 30 };
        clearBtn = new Button { Text = "Clear All", Left = 240, Top = 40, Height = 30 };
        toExcelBtn = new Button { Text = "Create excel", Left = 320, Top = 40, Height = 30 };

        gen50Btn = new Button { Text = "Gen 50", Left = 320, Top = 10, Height = 30, Width = 90 };
        gen500Btn = new Button { Text = "Gen 500", Left = 430, Top = 10, Height = 30, Width = 90 };
        gen1600Btn = new Button { Text = "Gen 1600", Left = 540, Top = 10, Height = 30, Width = 90 };
        gen6000Btn = new Button { Text = "Gen 6000", Left = 650, Top = 10, Height = 30, Width = 90 };
        gen9500Btn = new Button { Text = "Gen 9500", Left = 760, Top = 10, Height = 30, Width = 90 };

        bstTreeView = new TreeView { Left = 10, Top = 80, Width = 280, Height = 300 };
        avlTreeView = new TreeView { Left = 320, Top = 80, Width = 280, Height = 300 };

        infoLabel = new Label { Left = 10, Top = 400, Width = 900, Height = 50 };

        var bstContextMenu = new ContextMenuStrip();
        var avlContextMenu = new ContextMenuStrip();
        bstContextMenu.Items.Add("Copy to Clipboard", null, CopyToClipboard_BST);
        avlContextMenu.Items.Add("Copy to Clipboard", null, CopyToClipboard_AVL);

        bstTreeView.ContextMenuStrip = bstContextMenu;
        avlTreeView.ContextMenuStrip = avlContextMenu;

        Controls.AddRange(new Control[]
        {
            inputBox, clearBtn, loadBtn, addBtn, deleteBtn, findBtn,
            gen50Btn, gen500Btn, gen1600Btn, gen6000Btn, gen9500Btn,
            avlTreeView, bstTreeView, infoLabel, toExcelBtn
        });

        loadBtn.Click += LoadBtn_Click;
        addBtn.Click += AddBtn_Click;
        deleteBtn.Click += DeleteBtn_Click;
        findBtn.Click += FindBtn_Click;
        clearBtn.Click += ClearBtn_Click;
        toExcelBtn.Click += (s, e) => new TreeToExcel().RunBenchmarksAndExportToExcel();

        gen50Btn.Click += (s, e) => GenerateData(50);
        gen500Btn.Click += (s, e) => GenerateData(500);
        gen1600Btn.Click += (s, e) => GenerateData(1600);
        gen6000Btn.Click += (s, e) => GenerateData(6000);
        gen9500Btn.Click += (s, e) => GenerateData(9500);
    }

    private void CopyToClipboard_BST(object sender, EventArgs e)
    {
        var treeData = GetTreeData(treeHead.Root);
        Clipboard.SetText(treeData);
    }

    private void CopyToClipboard_AVL(object sender, EventArgs e)
    {
        var treeData = GetTreeData(avlTreeHead.Root);
        Clipboard.SetText(treeData);
    }

    private string GetTreeData(dynamic node)
    {
        if (node == null) return string.Empty;

        var result = node.Phone + Environment.NewLine;
        result += GetTreeData(node.Left);
        result += GetTreeData(node.Right);

        return result;
    }

    private void GenerateData(int count)
    {
        avlTreeHead = new AvlTree();
        treeHead = new BinarySearchTree();

        var random = new Random();
        var phones = Enumerable.Range(0, count)
            .Select(i => random.NextInt64(70000000000, 79999999999))
            .ToList();

        var sw = Stopwatch.StartNew();
        foreach (var phone in phones)
            treeHead.Add(phone.ToString());
        var treeTime = sw.Elapsed.TotalMicroseconds;

        sw.Restart();
        foreach (var phone in phones)
            avlTreeHead.Add(phone.ToString());
        var avlTreeTime = sw.Elapsed.TotalMicroseconds;

        infoLabel.Text = $"Generated {count}: Tree {treeTime:F4}ms, AvlTree {avlTreeTime:F4}ms";
        RefreshUI();
    }

    private void LoadBtn_Click(object sender, EventArgs e)
    {
        var dialog = new OpenFileDialog();
        if (dialog.ShowDialog() != DialogResult.OK) return;

        avlTreeHead = new AvlTree();

        var lines = File.ReadAllLines(dialog.FileName);
        foreach (var line in lines)
        {
            var number = line.Trim();
            if (!string.IsNullOrEmpty(number))
            {
                avlTreeHead.Add(number);
                treeHead.Add(number);
            }
        }

        RefreshUI();
    }

    private void AddBtn_Click(object sender, EventArgs e)
    {
        var phone = inputBox.Text.Trim();
        if (string.IsNullOrWhiteSpace(phone)) return;

        var sw = Stopwatch.StartNew();
        treeHead.Add(phone);
        var elapsed = sw.Elapsed;

        sw.Restart();
        avlTreeHead.Add(phone);
        var elapsedByAvl = sw.Elapsed;

        infoLabel.Text = $"Added: Tree {elapsed.TotalMilliseconds:F4}ms AvlTree {elapsedByAvl.TotalMilliseconds:F4}ms";
        RefreshUI();
    }

    private void DeleteBtn_Click(object sender, EventArgs e)
    {
        var phone = inputBox.Text.Trim();
        if (string.IsNullOrWhiteSpace(phone)) return;

        var sw = Stopwatch.StartNew();
        treeHead.Delete(phone);
        var elapsed = sw.Elapsed;

        sw.Restart();
        avlTreeHead.Delete(phone);
        var elapsedByAvl = sw.Elapsed;

        infoLabel.Text =
            $"Deleted: Tree {elapsed.TotalMilliseconds:F4}ms AvlTree {elapsedByAvl.TotalMilliseconds:F4}ms";
        RefreshUI();
    }

    private void FindBtn_Click(object sender, EventArgs e)
    {
        var phone = inputBox.Text.Trim();
        if (string.IsNullOrWhiteSpace(phone)) return;

        var sw = Stopwatch.StartNew();
        var found = treeHead.Find(phone);
        var elapsed = sw.Elapsed;

        sw.Restart();
        var foundInAvlTree = avlTreeHead.Find(phone);
        var elapsedByAvl = sw.Elapsed;


        infoLabel.Text =
            $"Found: Tree {elapsed.TotalMilliseconds:F4}ms ({found != null}) AvlTree {elapsedByAvl.TotalMilliseconds:F4}ms ({foundInAvlTree != null})";
    }

    private void ClearBtn_Click(object sender, EventArgs e)
    {
        avlTreeHead = new AvlTree();
        treeHead = new BinarySearchTree();
        RefreshUI();
        infoLabel.Text = "Cleared All";
    }

    private void RefreshUI()
    {
        avlTreeView.Nodes.Clear();
        bstTreeView.Nodes.Clear();

        DisplayTree(avlTreeHead.Root, null, avlTreeView);
        DisplayTree(treeHead.Root, null, bstTreeView);

        infoLabel.Text +=
            $" | BST: Count={treeHead.Count()}, Leaves={treeHead.Leaves()}, Depth={treeHead.Depth()} | " +
            $"AVL: Count={avlTreeHead.Count()}, Leaves={avlTreeHead.Leaves()}, Depth={avlTreeHead.Depth()}";
    }

    private void DisplayTree(dynamic node, TreeNode parent, TreeView view)
    {
        if (node == null) return;

        var treeNode = new TreeNode(node.Phone);
        if (parent == null)
            view.Nodes.Add(treeNode);
        else
            parent.Nodes.Add(treeNode);

        DisplayTree(node.Left, treeNode, view);
        DisplayTree(node.Right, treeNode, view);
    }

    private void Form1_Load(object sender, EventArgs e)
    {
    }
}