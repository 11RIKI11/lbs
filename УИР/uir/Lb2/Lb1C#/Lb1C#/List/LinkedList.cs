namespace Lb1C_.List;

public class LinkedList
{
    public Node Head { get; private set; }
    public int Count { get; private set; }

    public void Add(DateTime time)
    {
        var node = new Node(time);
        if (Head == null)
        {
            Head = node;
        }
        else
        {
            var current = Head;
            while (current.Next != null)
                current = current.Next;
            current.Next = node;
        }

        Count++;
    }

    public bool Delete(DateTime time)
    {
        Node current = Head;
        Node prev = null;
        while (current != null)
        {
            if (current.Time == time)
            {
                if (prev == null)
                    Head = current.Next;
                else
                    prev.Next = current.Next;
                Count--;
                return true;
            }

            prev = current;
            current = current.Next;
        }

        return false;
    }

    public Node Find(DateTime time)
    {
        var current = Head;
        while (current != null)
        {
            if (current.Time == time)
                return current;
            current = current.Next;
        }

        return null;
    }

    public void Clear()
    {
        Head = null;
        Count = 0;
    }
}