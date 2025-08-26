namespace Lb1C_.Tree;

public class BSTreeNode
{
    public BSTreeNode(string phone)
    {
        Phone = phone;
    }

    public string Phone { get; set; }
    public BSTreeNode Left { get; set; }
    public BSTreeNode Right { get; set; }
}