# Igwe

Otu is a PHP-based package that handles fine-formatting of string representation of (very) large numbers with their 'magnitude'. For instance, "50m" means 50000000. We can decide to write the entire 50000000 or just pass it to this package in the form of "50m" and have it take care of the presentation. It also works vice-versa. Walk with me.

> As typical of my packages, each usage introduces you to a new word in Igbo language. You can be sure that "otu" is an Igbo word, and it means "one" which is actually a number anyway. You only need this "one" package, and without any dependencies, to improve productivity...#winks.

## Installation

To get started all you need to do is:

```php
composer require sirmekus/otu
```

That's all.

> Please note that as at this time, we only support the following 'magnitudes' (it is case-insensitive):
k: Thousand,
m: Million,
b: Billion,
t: trillion
---

|  Supported "magnitude"/symbol | Magnitude  | Example  |  Figure |   |
|---|---|---|---|---|
|  k |  Thousand |  "50k" | 50000  |   |
|  m | Million  | "40m"  | 40000000  |   |
|  b | Billion  |  "50b" | 50000000000  |   |
|  t |  Trillion |  "1t" | 1000000000000  |   |

## Usage

---

## Convert a string representation to a number

Example:

```php
require_once 'vendor/autoload.php';

use Emmy\App\Assistant\Otu;

$converted = Otu::convertToNumber("89k");
//Output: 89000

$converted = Otu::convertToNumber("89.54k");
//Output: 89540

$converted = Otu::convertToNumber("89.5k");
//Output: 89500

$converted = Otu::convertToNumber("50.6M");
//Output: 50600000

//etc.
```

If you would like to present it in a more friendly human-readable format
```php
$converted = Otu::format("50.6M");
//Output: 50,600,000.00
```

## Abbreviate/represent a large number with its 'magnitude' 

Example:

```php
require_once 'vendor/autoload.php';

use Emmy\App\Assistant\Otu;

$converted = Otu::abbreviate(290450);
//Output: 290.5K

$converted = Otu::abbreviate(290450, round:false);
//Output (prevent rounding up "decimal" part): 290.4K

//Specify output in 2 'decimal' places
$converted = Otu::abbreviate(290456,2);
//Output: 290.46K

$converted = Otu::abbreviate(290450, useUnit:false);
//Output (full specification of the magnitude): 290.5 thousand

```
## Meanwhile

 You can connect with me on [LinkedIn](https://www.linkedin.com/in/sirmekus) for insightful tips, and so we can grow our networks together.

 Check our educational platform for High Schools: [i-runs](https://www.i-runs.com).

 And follow me on [Twitter](https://www.twitter.com/Sire_Mekus).

 We can also catch fun on [Tiktok](https://www.tiktok.com/@emmybuoy?_t=8luE6m2o0rV&_r=1).

 Join forces with me on [Instagram](https://www.instagram.com/sir_mekus/?igsh=MWN1c3ZoNzFmdnR0)

 I encourage contribution even if it's in the documentation. Thank you, and I really hope you find this package helpful.
