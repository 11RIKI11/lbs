namespace Lb1C_.List;

public class Node
{
    public Node(DateTime time)
    {
        Time = time;
    }

    public DateTime Time { get; set; }
    public Node Next { get; set; }
}