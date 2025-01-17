
<?php
class SpecificTransactionsIterator extends MyIterator {
    private $transactions = new SplFixedArray(100);
    public function __construct(SplFixedArray $transactions) {
        $this->transactions = $transactions;
        $this->position = 0;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function current(): mixed {
        return $this->transactions[$this->position];
    }

    public function key(): int {
        return $this->position;
    }

    public function next(): mixed{
        return $this->transactions[++$this->position];
    }

    public function valid(): bool {
        return $this->position >= 0 && $this->position < $this->transactions->getSize();
    }
    public function clear(): void {
        for ($i = 0; $i < $this->transactions->getSize(); $i++) {
            $this->transactions[$i] = null; 
        }    }
}

?>