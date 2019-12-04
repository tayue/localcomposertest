<?php
// a simple foreach() to traverse the SPL class names
foreach(spl_classes() as $key=>$value)
{
    echo $key.' ->'.$value.'<br />';
}

//output:
/**
 *
            AppendIterator ->AppendIterator
            ArrayIterator ->ArrayIterator
            ArrayObject ->ArrayObject
            BadFunctionCallException ->BadFunctionCallException
            BadMethodCallException ->BadMethodCallException
            CachingIterator ->CachingIterator
            CallbackFilterIterator ->CallbackFilterIterator
            DirectoryIterator ->DirectoryIterator
            DomainException ->DomainException
            EmptyIterator ->EmptyIterator
            FilesystemIterator ->FilesystemIterator
            FilterIterator ->FilterIterator
            GlobIterator ->GlobIterator
            InfiniteIterator ->InfiniteIterator
            InvalidArgumentException ->InvalidArgumentException
            IteratorIterator ->IteratorIterator
            LengthException ->LengthException
            LimitIterator ->LimitIterator
            LogicException ->LogicException
            MultipleIterator ->MultipleIterator
            NoRewindIterator ->NoRewindIterator
            OuterIterator ->OuterIterator
            OutOfBoundsException ->OutOfBoundsException
            OutOfRangeException ->OutOfRangeException
            OverflowException ->OverflowException
            ParentIterator ->ParentIterator
            RangeException ->RangeException
            RecursiveArrayIterator ->RecursiveArrayIterator
            RecursiveCachingIterator ->RecursiveCachingIterator
            RecursiveCallbackFilterIterator ->RecursiveCallbackFilterIterator
            RecursiveDirectoryIterator ->RecursiveDirectoryIterator
            RecursiveFilterIterator ->RecursiveFilterIterator
            RecursiveIterator ->RecursiveIterator
            RecursiveIteratorIterator ->RecursiveIteratorIterator
            RecursiveRegexIterator ->RecursiveRegexIterator
            RecursiveTreeIterator ->RecursiveTreeIterator
            RegexIterator ->RegexIterator
            RuntimeException ->RuntimeException
            SeekableIterator ->SeekableIterator
            SplDoublyLinkedList ->SplDoublyLinkedList
            SplFileInfo ->SplFileInfo
            SplFileObject ->SplFileObject
            SplFixedArray ->SplFixedArray
            SplHeap ->SplHeap
            SplMinHeap ->SplMinHeap
            SplMaxHeap ->SplMaxHeap
            SplObjectStorage ->SplObjectStorage
            SplObserver ->SplObserver
            SplPriorityQueue ->SplPriorityQueue
            SplQueue ->SplQueue
            SplStack ->SplStack
            SplSubject ->SplSubject
            SplTempFileObject ->SplTempFileObject
            UnderflowException ->UnderflowException
            UnexpectedValueException ->UnexpectedValueException
 *
 *
 *
 */