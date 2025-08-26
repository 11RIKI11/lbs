namespace Lb1C_.Tree;

public class BinarySearchTree
{
    public BSTreeNode Root { get; private set; }

    public void Add(string phone)
    {
        if (!IsValidPhone(phone)) return;
        Root = Add(Root, phone);
    }

    private BSTreeNode Add(BSTreeNode node, string phone)
    {
        if (node == null) return new BSTreeNode(phone);

        if (string.Compare(phone, node.Phone) < 0)
            node.Left = Add(node.Left, phone);
        else if (string.Compare(phone, node.Phone) > 0)
            node.Right = Add(node.Right, phone);
        // дубликаты игнорируются
        return node;
    }

    public bool Delete(string phone)
    {
        if (!IsValidPhone(phone)) return false;
        var found = false;
        Root = Delete(Root, phone, ref found);
        return found;
    }

    private BSTreeNode Delete(BSTreeNode node, string phone, ref bool found)
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

        return node;
    }

    private BSTreeNode FindMin(BSTreeNode node)
    {
        while (node.Left != null)
            node = node.Left;
        return node;
    }

    public BSTreeNode Find(string phone)
    {
        return Find(Root, phone);
    }

    private BSTreeNode Find(BSTreeNode node, string phone)
    {
        if (node == null || node.Phone == phone) return node;

        return string.Compare(phone, node.Phone) < 0
            ? Find(node.Left, phone)
            : Find(node.Right, phone);
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
        return Depth(Root);
    }

    private int Count(BSTreeNode node)
    {
        return node == null ? 0 : 1 + Count(node.Left) + Count(node.Right);
    }

    private int Leaves(BSTreeNode node)
    {
        return node == null ? 0 :
            node.Left == null && node.Right == null ? 1 :
            Leaves(node.Left) + Leaves(node.Right);
    }

    private int Depth(BSTreeNode node)
    {
        return node == null ? 0 : 1 + Math.Max(Depth(node.Left), Depth(node.Right));
    }

    private bool IsValidPhone(string phone)
    {
        return phone.StartsWith("7") && phone.All(char.IsDigit) && phone.Length >= 10;
    }
}