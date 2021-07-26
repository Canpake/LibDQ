<?php
/**
 * Name    : Model - DQ Library
 * Creator : Jonathan Yim <jyim1120@gmail.com>
 * Detail  : Helper functions for making dynamic questions
 *
 */

// require_once __DIR__ . '/vendor/autoload.php';
// use MathPHP\Algebra;

class Lib_DQ {
    /*
     * This class holds a number of functions for writing dynamic questions. Currently, the following functions exist:
     * Arithmetic:
     *  - factors           (finds the factors of a given number)
     *  - pythag_triplet    (generates pythagorean triples)
     *  - sigfig            (rounds a number to a given number of significant figures)
     *
     * Graphing:
     *  - get_y_intercept   (finds y-intercept of line, given its gradient and a point it passes through)
     *  - get_line          (finds gradient and y-intercept of a line passing through 2 given points)
     *  - get_intercept     (finds intercept of 2 lines given their gradients and y-intercepts)
     *
     * Expressions/Algebra:
     *  - gen_eq            (properly formats an equation, given as two arrays)
     *  - gen_expr          (properly formats an expression, given as an array)
     *  - gen_term          (properly formats a term, given as a coefficient and variable)
     *  - gen_frac          (properly formats a fraction, given numerator and denominator)
     */

    // // Singleton pattern
    // protected function __construct() {}     // private constructor to prevent direct construction calls with `new`
    // protected function __clone() {}         // should not be cloneable
    // public function __wakeup() {            // should not be restorable from strings
    //     throw new Exception("Cannot unserialize a singleton.");
    // }

    protected static $instance = null;

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    // Test function to check if operational
    public static function ping() {
        echo "DQ Library is loaded!";

        // $test = Algebra::gcd(8, 12);
        // echo "The GCD of 8 and 12 is $test.";
    }

    /* --- Arithmetic Functions --- */
    /**
     * Takes in an integer and returns an array of its factors. The array is not sorted.
     * @param $n - The number to find factors of. Should be positive.
     * @return array - An array of its factors, including 1 and itself.
     */
    public static function factors($n){
        $factors_array = array();
        for ($x = 1; $x <= sqrt(abs($n)); $x++)
        {
            if ($n % $x == 0)
            {
                $z = $n/$x;
                array_push($factors_array, $x, $z);
            }
        }

        return $factors_array;
    }
 
    /**
     * Generate a pythagorean triplet, returned as an array
     * @param $max - A number, representing the highest number that can be generated; should be greater than 4.
     * @return array - An array - base, height and hypotenuse.
     */
    public static function pythag_triplet($max) {
        // Generate a pythagorean triple with 2 variables m > n
        $max_m = floor((1 + sqrt(2*$max - 1)) / 2);  // calculated from quadratic eq: m^2 + (m-1)^2 ≤ max
        $m = mt_rand(2, $max_m);
        $n = mt_rand(1, $m-1);

        // Use (m^2 - n^2), 2mn and m^2 + n^2
        $arr = [$m**2 - $n**2, 2*$m*$n];

        list($b, $h) = $arr;
        $hypo = $m**2 + $n**2;
        return [$b, $h, $hypo];
    }

    /**
     * Returns a number rounded to a certain significant figure.
     * @param $value - The number to round
     * @param $digits - The number of significant figures
     * @return string - The rounded value.
     */
    public static function sigfig($value, $digits) {
        if (strlen((string) abs($value)) <= $digits) {
            return ((string) $value);
        }

        if ($value == 0) {
            $decimalPlaces = $digits - 1;
        } else {
            $decimalPlaces = $digits - floor(log10(abs($value))) - 1;
        }

        if ($decimalPlaces > 0) {
            return number_format($value, $decimalPlaces, '.', '');
        } else if ($decimalPlaces == 0) {
            return round($value, $decimalPlaces) . ".";
        } else {
            return round($value, $decimalPlaces);
        }
    }

    /* --- Graphing-related Functions --- */
    /**
     * Finds the y-intercept value of a line y = mx + c, given a gradient (m) and point (x, y).
     * @param $gradient - The gradient of the line.
     * @param $point - A 2-length array representing a point, i.e. its x and y-coordinate.
     * @return float|int|mixed - The y-intercept of the line.
     */
    public static function get_y_intercept($gradient, $point) {
        // y = ax + b => b = y - ax
        return $point[1] - $gradient*$point[0];
    }

    /**
     * Finds the gradient and intercept of a line that passes through two given points.
     * @param $pt1 - A 2-length array representing the point [x, y].
     * @param $pt2 - A 2-length array representing the point [x, y].
     * @return array - A 2-length array, noting the gradient and the intercept of the line respectively.
     */
    public static function get_line($pt1, $pt2) {
        $y_diff = $pt2[1] - $pt1[1];
        $x_diff = $pt2[0] - $pt1[0];

        $grad = $y_diff / $x_diff;
        $intercept = $pt1[1] - $grad * $pt1[0];  // y = mx + c; so c = y - mx
        return [$grad, $intercept];
    }

    /**
     * Finds the intercept between two lines given their gradients (m1/m2) and y-intercepts (c1/c2), i.e.:
     * y = (m1)x + c1  and  y = (m2)x + c2.
     * @param $m1 - Gradient of the first line.
     * @param $c1 - y-intercept of the first line.
     * @param $m2 - Gradient of the second line.
     * @param $c2 - y-intercept of the second line.
     * @return array - A 2-length array representing a point [x, y].
     */
    public static function get_intercept($m1, $c1, $m2, $c2) {
        // (m1)x + c1 = (m2)x + c2
        // => x = (c2-c1) / (m1-m2)
        // => y = m1x + c1
        $x = ($c2 - $c1) / ($m1 - $m2);
        $y = $m1*$x + $c1;
        return [$x, $y];
    }

    /**
     * Finds the distance between two points.
     * @param $pt1 - A 2-length array representing the point [x, y].
     * @param $pt2 - A 2-length array representing the point [x, y].
     * @return float - The distance between the two points.
     */
    public static function get_distance($pt1, $pt2) {
        list($dx, $dy) = [$pt1[0]-$pt2[0], $pt1[1]-$pt2[1]];
        return sqrt(pow($dx, 2) + pow($dy, 2));
    }

    /* --- Expression-related Functions --- */
    /**
     * A function that generates a simple linear equation and returns an appropriate string.
     * @param $lhs - Array, (terms => coefficients); displayed in order given, left of $operator.
     * @param $rhs - Array, (terms => coefficients); displayed in order given, right of $operator.
     * @param $operator - String. The operator between $lhs and $rhs; defaults to "=".
     * @param $backticks - Boolean. Whether to encase the result in backticks. False by default.
     * @return string - A string representing the function.
     */
    public static function gen_eq($lhs, $rhs, $operator="=", $backticks=false) {
        $eq = Lib_DQ::gen_expr($lhs) . " $operator " . Lib_DQ::gen_expr($rhs);
        if ($backticks) $eq = "`" . $eq . "`";
        return $eq;
    }

    /**
     * A function that generates an expression from an array of terms => coefficients and returns a string.
     * @param $term_array - Array, (terms => coefficients); displayed in order given.
     * @return string - The expression.
     */
    public static function gen_expr($term_array) {
        $expr = "";
        $first = True;
        foreach ($term_array as $term => $coef) {
            // skip terms where coefficient is 0
            if ((is_int($coef) or is_double($coef)) and $coef == 0) continue;

            $keep_1 = ($term == "");    // keep 1 if the term is empty
            $expr .= Lib_DQ::gen_term($coef, $term, !$first, $keep_1);
            $first = False;
        }
        // if expression is still empty, return 0.
        if ($expr == "") return "0";
        else return $expr;
    }

    /**
     * A function that generates a term from an integer coefficient and returns a string.
     * @param $coef - Integer or String. If integer, coefficient of the term;
     *                                   If string, a preformatted coefficient (e.g. fractions).
     * @param $var - String. How the variable of the term will be expressed.
     * @param $include_plus - Boolean. Whether to include an initial '+' if the coefficient is positive. True by default.
     * @param $keep_1 - Boolean. If coefficient is 1 or -1, whether to keep in string. False by default.
     * @return string - The expression.
     */
    public static function gen_term($coef, $var, $include_plus=true, $keep_1=false) {
        if (is_string($coef)) return $coef . $var;

        if ($coef == 0) {
            return ($include_plus) ? "+0" : "0";
        }

        $expr = $var;
        // add on coefficient if keep_1 is true or coefficient not 1/-1
        if ($keep_1 or abs($coef) != 1) $expr = strval(abs($coef)) . $expr;
        if ($coef >= 0) {
            if ($include_plus) $expr = "+" . $expr;
        } else {
            $expr = "-" . $expr;
        }

        return $expr;
    }

    /**
     * A function that generates a fraction or number to be displayed through AsciiMath based on numerator and denominator.
     * @param $numer - Integer. Numerator of the fraction. 
     * @param $denom - Integer. Denominator of the fraction. Function returns 0 if this is 0.
     * @param $include_plus - Boolean. Whether to include an initial '+' if the coefficient is positive. False by default.
     * @return int|string - The expression. String if a fraction; integer if it can be simplified.
     */
    function gen_frac($numer, $denom, $include_plus=false) {
        // check if either value is 0
        if ($numer == 0 || $denom == 0) { return 0; }

        $gcd = gmp_intval(gmp_gcd($numer, $denom));
        $positive = ($numer * $denom > 0);   // boolean to check if fraction positive
        $numer = abs(intdiv($numer, $gcd));  // simplified numerator
        $denom = abs(intdiv($denom, $gcd));  // simplified denominator
        
        // return as int if whole number
        if ($denom == 1) {
            return ($positive) ? $numer : -$numer;
        }
        // return as string otherwise
        if ($positive && $include_plus) {
            return "+ $numer / $denom";
        } else if ($positive) {
            return "$numer / $denom";
        } else {
            return "- $numer / $denom";
        }
    }

    /**
     * Function that takes a number and returns its simplified square root for display in AsciiMath.
     * @param $sqrt_value - Integer. The number to take the square root of. Can be negative; will return with 'i' as appropriate.
     * @return int|string - Int if square root is a positive perfect square; String of the simplified square root otherwise.
     */
    function simplify_sqrt($sqrt_value) {
        if (!is_int($sqrt_value)) return null;

        $positive = $sqrt_value >= 0;
        $sqrt_value = abs($sqrt_value);

        // If square root, return directly
        if (gmp_perfect_square($sqrt_value)) {
            $value = round(sqrt($sqrt_value));

            if ($positive) {
                return $value;
            } else {
                // either return $value i or i if $value is 1
                return ($value == 1) ? "i" : "$value i";
            }
        }

        $divisor = 2;
        $inside_root = $sqrt_value;
        $outside_root = 1;

        while ($divisor * $divisor <= $inside_root) {
            if ($inside_root % ($divisor * $divisor) == 0) {
                $inside_root /= ($divisor * $divisor);
                $outside_root *= $divisor;
            } else { $divisor += 1; }
        }

        if ($outside_root == 1) {
            // cannot be simplified; omit the '1'
            return ($positive) ? "sqrt($inside_root)" : "i sqrt($inside_root)";
        } else {
            return ($positive) ? "$outside_root sqrt($inside_root)" : "$outside_root i sqrt($inside_root)";
        }
    }

}