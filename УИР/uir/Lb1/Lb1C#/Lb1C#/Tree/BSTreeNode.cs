namespace Lb1C_.Tree;

public class BSTreeNode
{
    public BSTreeNode(DateTime time)
    {
        Time = time;
    }

    public DateTime Time { get; set; }
    public BSTreeNode Left { get; set; }
    public BSTreeNode Right { get; set; }
}