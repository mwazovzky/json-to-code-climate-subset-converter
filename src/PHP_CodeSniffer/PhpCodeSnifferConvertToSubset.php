<?php

namespace BeechIt\JsonToCodeClimateSubsetConverter\PHP_CodeSniffer;

use BeechIt\JsonToCodeClimateSubsetConverter\AbstractConverter;
use BeechIt\JsonToCodeClimateSubsetConverter\InvalidJsonException;
use BeechIt\JsonToCodeClimateSubsetConverter\ConvertToSubsetInterface;

final class PhpCodeSnifferConvertToSubset extends AbstractConverter implements ConvertToSubsetInterface
{
    protected function getToolName(): string
    {
        return 'PHP_CodeSniffer';
    }

    public function convertToSubset(): void
    {
        try {
            $this->abstractJsonValidator->validateJson();

            foreach ($this->json->files as $filename => $file) {
                foreach ($file->messages as $node) {
                    $this->codeClimateNodes[] = [
                        'description' => $this->createDescription($node->message),
                        'fingerprint' => $this->createFingerprint(
                            $node->message,
                            $filename,
                            $node->line
                        ),
                        'location' => [
                            'path' => $filename,
                            'lines' => [
                                'begin' => $node->line,
                            ],
                        ],
                    ];
                }
            }
        } catch (InvalidJsonException $exception) {
            throw $exception;
        }
    }
}