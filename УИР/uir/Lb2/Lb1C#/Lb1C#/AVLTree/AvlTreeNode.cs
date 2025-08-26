namespace Lb1C_.AVLTree;

public class AvlTreeNode
{
    public int Height;
    public AvlTreeNode Left, Right;
    public string Phone;

    public AvlTreeNode(string phone)
    {
        Phone = phone;
        Height = 1;
    }
}