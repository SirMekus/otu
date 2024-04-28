# Igwe

Otu is PHP-based package to handle fine-formatting of string representation of numbers with their magnitude. For instance, "50m" means 50,000,000. We can decide to write the entire 50,000,000 or just pass it to this package in the form of "50m" and have it take care of the presentation.

> As typical of my packages, each usage introduces you to a new work in the Igbo language. You can be sure that "otu" is an Igbo word, and it means "one" which is actually a number anyway. You only need this "one" package, and without any dependencies, to improve productivity...#winks.

## Installation

To get started all you need to do is:

```php
composer require sirmekus/otu
```

That's all.

> Please note that as at this time, we only support the following magnitudes:
k: Thousand,
m: Million,
b: Billion,
t: trillion
---


## Usage

---

## Convert a string representation to a number

Example:

```php
require_once 'vendor/autoload.php';

use Emmy\Assistant\Otu;

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

If you would like to present it a more friendly human-readable format
```php
$converted = Otu::format("50.6M");
//Output: 50,600,000.00
```

## Meanwhile

 You can connect with me on [LinkedIn](https://www.linkedin.com/in/sirmekus) for insightful tips and so we can grow our networks together.

 Check our educational platform for High Schools [i-runs](https://www.i-runs.com).

 And follow me on [Twitter](https://www.twitter.com/Sire_Mekus).

 We can also catch fun [Tiktok](https://www.tiktok.com/@emmybuoy?_t=8luE6m2o0rV&_r=1).

 Join forces with me on [Instagram](https://www.instagram.com/sir_mekus/?igsh=MWN1c3ZoNzFmdnR0)

 I encourage contribution even if it's in the documentation. Thank you, and I really hope you find this package helpful.
