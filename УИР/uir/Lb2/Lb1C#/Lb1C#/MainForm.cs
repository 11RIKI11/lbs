using System.Diagnostics;
using Lb1C_.AVLTree;
using Lb1C_.Tree;

namespace Lb1C_;

public partial class MainForm : Form
{
    private AvlTree avlTreeHead = new();
    private TreeView avlTreeView;
    private TreeView bstTreeView;

    private TextBox genCountBox;
    private Button generateBtn;

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
        BackColor = Color.FromArgb(245, 245, 250); // светло-серый фон

        inputBox = new TextBox { Left = 10, Top = 10, Width = 200 };
        loadBtn = new Button { Text = "Загрузить из файла", Left = 220, Top = 10, Height = 30 };
        addBtn = new Button { Text = "Добавить", Left = 10, Top = 40, Height = 30 };
        deleteBtn = new Button { Text = "Удалить", Left = 80, Top = 40, Height = 30 };
        findBtn = new Button { Text = "Найти", Left = 160, Top = 40, Height = 30 };
        clearBtn = new Button { Text = "Удалить всё", Left = 240, Top = 40, Height = 30 };
        toExcelBtn = new Button { Text = "Создать excel", Left = 320, Top = 40, Height = 30 };

        genCountBox = new TextBox{ Left = 620, Top = 10, Width = 100, Height = 30, PlaceholderText = "Кол-во"};

        generateBtn = new Button{ Text = "Сгенерировать", Left = 730, Top = 10, Width = 120, Height = 30 };


        bstTreeView = new TreeView { Left = 10, Top = 80, Width = 280, Height = 300 };
        avlTreeView = new TreeView { Left = 320, Top = 80, Width = 280, Height = 300 };

        infoLabel = new Label { Left = 10, Top = 400, Width = 900, Height = 50 };

        genCountBox.BackColor = Color.White;
        genCountBox.ForeColor = Color.Black;

        inputBox.BackColor = Color.White;
        inputBox.ForeColor = Color.Black;

        infoLabel.BackColor = Color.WhiteSmoke;
        infoLabel.ForeColor = Color.DarkSlateGray;
        infoLabel.BorderStyle = BorderStyle.FixedSingle;

        //Верхняя зона
        inputBox.SetBounds(10, 10, 200, 30);
        loadBtn.SetBounds(220, 10, 150, 30);
        genCountBox.SetBounds(380, 10, 80, 30);
        generateBtn.SetBounds(470, 10, 120, 30);


        //Средняя зона
        addBtn.SetBounds(10, 50, 100, 30);
        deleteBtn.SetBounds(120, 50, 100, 30);
        findBtn.SetBounds(230, 50, 100, 30);
        clearBtn.SetBounds(340, 50, 120, 30);
        toExcelBtn.SetBounds(470, 50, 130, 30);

        //Деревья
        bstTreeView.SetBounds(10, 100, 380, 300);
        avlTreeView.SetBounds(400, 100, 380, 300);

        bstTreeView.BackColor = Color.White;
        avlTreeView.BackColor = Color.White;

        //Нижняя зона
        infoLabel.SetBounds(10, 410, 770, 40);


        var bstContextMenu = new ContextMenuStrip();
        var avlContextMenu = new ContextMenuStrip();
        bstContextMenu.Items.Add("Копировать", null, CopyToClipboard_BST);
        avlContextMenu.Items.Add("Копировать", null, CopyToClipboard_AVL);

        bstTreeView.ContextMenuStrip = bstContextMenu;
        avlTreeView.ContextMenuStrip = avlContextMenu;

        Controls.AddRange(new Control[]
        {
            inputBox, clearBtn, loadBtn, addBtn, deleteBtn, findBtn,
            avlTreeView, bstTreeView, infoLabel, toExcelBtn, genCountBox,
            generateBtn
        });

        loadBtn.Click += LoadBtn_Click;
        addBtn.Click += AddBtn_Click;
        deleteBtn.Click += DeleteBtn_Click;
        findBtn.Click += FindBtn_Click;
        clearBtn.Click += ClearBtn_Click;
        toExcelBtn.Click += (s, e) => new TreeToExcel().RunBenchmarksAndExportToExcel();

        generateBtn.Click += (s, e) =>
        {
            if (int.TryParse(genCountBox.Text.Trim(), out int count) && count > 0)
            {
                GenerateData(count);
            }
            else
            {
                MessageBox.Show("Введите корректное число для генерации", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Warning);
            }
        };
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