<?php

abstract class ReportsGenerationTemplate {
    public function generate(): void {
        $data = $this->getData();
        $formattedData = $this->formatData($data);
        $filteredData = $this->filterData($formattedData);
        $this->finalizeReport($filteredData);
    }

    protected function getData(): array {
        echo "Getting data...\n";
        return ['raw data'];
    }

    protected function formatData(array $data): array {
        echo "Formatting data...\n";
        return array_map('strtoupper', $data);
    }

    protected function filterData(array $data): array {
        echo "Filtering data...\n";
        return array_filter($data, fn($item) => strlen($item) > 3);
    }
    abstract protected function finalizeReport(array $data): void;
}
class reportGenerator extends ReportsGenerationTemplate {
    protected function finalizeReport(array $data): void {
        echo "Generating a detailed report...\n";
        foreach ($data as $item) {
            echo "- $item\n";
        }
    }
}

class statisticsGenerator extends ReportsGenerationTemplate {
    protected function finalizeReport(array $data): void {
        echo "Generating statistical analysis...\n";
        echo "Total items: " . count($data) . "\n";
    }
}
