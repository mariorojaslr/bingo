<?php

namespace App\Services;

class RouletteService
{
    /**
     * Números rojos en la ruleta europea.
     */
    const RED_NUMBERS = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 23, 25, 27, 30, 32, 34, 36];
    
    /**
     * Números negros en la ruleta europea.
     */
    const BLACK_NUMBERS = [2, 4, 6, 8, 10, 11, 13, 15, 17, 20, 22, 24, 26, 28, 29, 31, 33, 35];

    /**
     * Genera un número ganador y calcula el pago total de las apuestas.
     * 
     * @param array $bets Ejemplo: [['type' => 'straight', 'value' => '15', 'amount' => 10], ['type' => 'red', 'value' => null, 'amount' => 5]]
     * @param int|null $forcedNumber (Para tests o eventos especiales)
     * @return array
     */
    public function spinAndCalculate(array $bets, $forcedNumber = null)
    {
        // 1. Generar el número ganador de la ruleta (0 al 36)
        $winningNumber = is_null($forcedNumber) ? mt_rand(0, 36) : $forcedNumber;
        
        $totalBet = 0;
        $totalWon = 0;
        $winningBets = [];

        // 2. Procesar cada apuesta
        foreach ($bets as $bet) {
            $type = $bet['type'] ?? '';
            $value = $bet['value'] ?? null; // Puede ser un array de números o un string como 'red'
            $amount = floatval($bet['amount'] ?? 0);

            if ($amount <= 0) continue;

            $totalBet += $amount;
            $winAmount = 0;
            $multiplier = 0;
            
            $isWinner = $this->isWinningBet($winningNumber, $type, $value);

            if ($isWinner) {
                $multiplier = $this->getPayoutMultiplier($type);
                // El jugador recupera su apuesta inicial + la ganancia multiplicada
                $winAmount = $amount + ($amount * $multiplier);
                $totalWon += $winAmount;
                
                $winningBets[] = [
                    'type' => $type,
                    'value' => $value,
                    'amount' => $amount,
                    'won' => $winAmount
                ];
            }
        }

        return [
            'winningNumber' => $winningNumber,
            'isRed' => in_array($winningNumber, self::RED_NUMBERS),
            'isBlack' => in_array($winningNumber, self::BLACK_NUMBERS),
            'isEven' => $winningNumber > 0 && $winningNumber % 2 === 0,
            'isOdd' => $winningNumber > 0 && $winningNumber % 2 !== 0,
            'totalBet' => $totalBet,
            'totalWon' => $totalWon,
            'netProfit' => $totalWon - $totalBet,
            'winningBets' => $winningBets
        ];
    }

    /**
     * Verifica si una apuesta específica ganó contra el número ganador.
     */
    private function isWinningBet(int $winningNumber, string $type, $value): bool
    {
        // El 0 hace perder a todas las apuestas externas
        if ($winningNumber === 0) {
            if (in_array($type, ['straight', 'split', 'street', 'corner', 'sixline']) && is_array($value)) {
                return in_array(0, $value);
            }
            if ($type === 'straight' && (int)$value === 0) {
                return true;
            }
            return false;
        }

        switch ($type) {
            case 'straight':
            case 'split':
            case 'street':
            case 'corner':
            case 'sixline':
                // Para apuestas internas, $value debe ser un array de números apostados
                if (is_array($value)) {
                    return in_array($winningNumber, $value);
                } elseif (is_numeric($value)) {
                    return $winningNumber === (int)$value;
                }
                return false;

            case 'red':
                return in_array($winningNumber, self::RED_NUMBERS);
            
            case 'black':
                return in_array($winningNumber, self::BLACK_NUMBERS);
                
            case 'even':
                return $winningNumber % 2 === 0;
                
            case 'odd':
                return $winningNumber % 2 !== 0;
                
            case '1-18':
                return $winningNumber >= 1 && $winningNumber <= 18;
                
            case '19-36':
                return $winningNumber >= 19 && $winningNumber <= 36;
                
            case 'dozen1':
                return $winningNumber >= 1 && $winningNumber <= 12;
                
            case 'dozen2':
                return $winningNumber >= 13 && $winningNumber <= 24;
                
            case 'dozen3':
                return $winningNumber >= 25 && $winningNumber <= 36;
                
            case 'col1':
                return $winningNumber % 3 === 1;
                
            case 'col2':
                return $winningNumber % 3 === 2;
                
            case 'col3':
                return $winningNumber % 3 === 0;

            default:
                return false;
        }
    }

    /**
     * Devuelve el multiplicador de ganancia para un tipo de apuesta.
     * Ejemplo: Un Pleno ('straight') paga 35 a 1. (Devuelve 35)
     */
    private function getPayoutMultiplier(string $type): int
    {
        $multipliers = [
            'straight' => 35,
            'split' => 17,
            'street' => 11,
            'corner' => 8,
            'sixline' => 5,
            'dozen1' => 2,
            'dozen2' => 2,
            'dozen3' => 2,
            'col1' => 2,
            'col2' => 2,
            'col3' => 2,
            'red' => 1,
            'black' => 1,
            'even' => 1,
            'odd' => 1,
            '1-18' => 1,
            '19-36' => 1,
        ];

        return $multipliers[$type] ?? 0;
    }
}
