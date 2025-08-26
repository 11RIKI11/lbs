namespace Lb1C_.Tree;

public class BinarySearchTree
{
    public BSTreeNode Root { get; private set; }

    public void Add(DateTime time)
    {
        Root = Add(Root, time);
    }

    private BSTreeNode Add(BSTreeNode node, DateTime time)
    {
        if (node == null) return new BSTreeNode(time);

        if (time < node.Time)
            node.Left = Add(node.Left, time);
        else if (time >= node.Time)
            node.Right = Add(node.Right, time);
        return node;
    }

    public bool Delete(DateTime time)
    {
        var found = false;
        Root = Delete(Root, time, ref found);
        return found;
    }

    private BSTreeNode Delete(BSTreeNode node, DateTime time, ref bool found)
    {
        if (node == null) return null;
        if (time < node.Time)
        {
            node.Left = Delete(node.Left, time, ref found);
        }
        else if (time > node.Time)
        {
            node.Right = Delete(node.Right, time, ref found);
        }
        else
        {
            found = true;
            if (node.Left == null) return node.Right;
            if (node.Right == null) return node.Left;
            var min = FindMin(node.Right);
            node.Time = min.Time;
            node.Right = Delete(node.Right, min.Time, ref found);
        }

        return node;
    }

    private BSTreeNode FindMin(BSTreeNode node)
    {
        while (node.Left != null)
            node = node.Left;
        return node;
    }

    public BSTreeNode Find(DateTime time)
    {
        return Find(Root, time);
    }

    private BSTreeNode Find(BSTreeNode node, DateTime time)
    {
        if (node == null || node.Time == time) return node;

        return time < node.Time
            ? Find(node.Left, time)
            : Find(node.Right, time);
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
            node.Left == null && node.Right == null ? 1 : Leaves(node.Left) + Leaves(node.Right);
    }

    private int Depth(BSTreeNode node)
    {
        return node == null ? 0 : 1 + Math.Max(Depth(node.Left), Depth(node.Right));
    }
}