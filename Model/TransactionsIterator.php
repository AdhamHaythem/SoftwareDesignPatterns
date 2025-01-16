
<?php
class TransactionsIterator extends MyIterator {
    private $position = 0;
    private array $transactions;
    public function __construct(array $transactions) {
        $this->transactions = $transactions;
        $this->position = 0;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function current() {
        return $this->transactions[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next(){
        return $this->transactions[++$this->position];
    }

    public function valid(): bool {
        return isset($this->transactions[$this->position]);
    }
}

?>