namespace Lb1C_.AVLTree;

public class AvlTree
{
    public AvlTreeNode Root { get; private set; }

    public void Add(string phone)
    {
        if (!IsValidPhone(phone)) return;
        Root = Insert(Root, phone);
    }

    public bool Delete(string phone)
    {
        if (!IsValidPhone(phone)) return false;
        var found = false;
        Root = Delete(Root, phone, ref found);
        return found;
    }

    public AvlTreeNode Find(string phone)
    {
        return Find(Root, phone);
    }

    public int Count()
    {
        return Count(Root);
    }

    public int Leaves()
    {
        return Leaves(Root);
    }

    public int Depth()
    {
        return Height(Root);
    }

    private AvlTreeNode Insert(AvlTreeNode node, string phone)
    {
        if (node == null) return new AvlTreeNode(phone);

        var cmp = string.Compare(phone, node.Phone);
        if (cmp < 0)
            node.Left = Insert(node.Left, phone);
        else if (cmp > 0)
            node.Right = Insert(node.Right, phone);
        else
            return node;

        UpdateHeight(node);
        return Balance(node);
    }

    private AvlTreeNode Delete(AvlTreeNode node, string phone, ref bool found)
    {
        if (node == null) return null;

        var cmp = string.Compare(phone, node.Phone);
        if (cmp < 0)
        {
            node.Left = Delete(node.Left, phone, ref found);
        }
        else if (cmp > 0)
        {
            node.Right = Delete(node.Right, phone, ref found);
        }
        else
        {
            found = true;
            if (node.Left == null) return node.Right;
            if (node.Right == null) return node.Left;

            var min = FindMin(node.Right);
            node.Phone = min.Phone;
            node.Right = Delete(node.Right, min.Phone, ref found);
        }

        UpdateHeight(node);
        return Balance(node);
    }

    private AvlTreeNode Find(AvlTreeNode node, string phone)
    {
        if (node == null || node.Phone == phone) return node;

        return string.Compare(phone, node.Phone) < 0
            ? Find(node.Left, phone)
            : Find(node.Right, phone);
    }

    private AvlTreeNode FindMin(AvlTreeNode node)
    {
        while (node.Left != null)
            node = node.Left;
        return node;
    }

    private int Count(AvlTreeNode node)
    {
        return node == null ? 0 : 1 + Count(node.Left) + Count(node.Right);
    }

    private int Leaves(AvlTreeNode node)
    {
        return node == null ? 0 :
            node.Left == null && node.Right == null ? 1 :
            Leaves(node.Left) + Leaves(node.Right);
    }

    private int Height(AvlTreeNode node)
    {
        return node?.Height ?? 0;
    }

    private void UpdateHeight(AvlTreeNode node)
    {
        node.Height = 1 + Math.Max(Height(node.Left), Height(node.Right));
    }

    private int BalanceFactor(AvlTreeNode node)
    {
        return Height(node.Left) - Height(node.Right);
    }

    private AvlTreeNode Balance(AvlTreeNode node)
    {
        var balance = BalanceFactor(node);

        if (balance > 1)
        {
            if (BalanceFactor(node.Left) < 0)
                node.Left = RotateLeft(node.Left);
            return RotateRight(node);
        }

        if (balance < -1)
        {
            if (BalanceFactor(node.Right) > 0)
                node.Right = RotateRight(node.Right);
            return RotateLeft(node);
        }

        return node;
    }

    private AvlTreeNode RotateRight(AvlTreeNode y)
    {
        var x = y.Left;
        var T2 = x.Right;

        x.Right = y;
        y.Left = T2;

        UpdateHeight(y);
        UpdateHeight(x);

        return x;
    }

    private AvlTreeNode RotateLeft(AvlTreeNode x)
    {
        var y = x.Right;
        var T2 = y.Left;

        y.Left = x;
        x.Right = T2;

        UpdateHeight(x);
        UpdateHeight(y);

        return y;
    }

    private bool IsValidPhone(string phone)
    {
        return phone.StartsWith("7") && phone.All(char.IsDigit) && phone.Length >= 10;
    }
}