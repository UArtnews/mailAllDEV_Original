# Changelog since 1.4.4
* Made the class compliant with PSR4

# Changelog since 1.4.3
* Removed the .lock-file for real.

# Changelog since 1.4.2
* Refactor specificity, thx to [barryvdh](https://github.com/barryvdh),
    for more information see the [Pull Request](https://github.com/tijsverkoyen/CssToInlineStyles/pull/59)

# Changelog since 1.4.1
* Ignore the composer.lock-file as it doesn't make any sense
* Tests. Massive thumbs up for [jbboehr](https://github.com/jbboehr),
    for more information see the [Pull Request](https://github.com/tijsverkoyen/CssToInlineStyles/pull/61)
* Tweak for the `!important` attributes-fix, thx to [barryvdh](https://github.com/barryvdh),
    for more information see the [Pull Request](https://github.com/tijsverkoyen/CssToInlineStyles/pull/62)

# Changelog since 1.4.0
* Skip `!important` attributes if needed, thx to [barryvdh](https://github.com/barryvdh),
    for more information see the [Pull Request](https://github.com/tijsverkoyen/CssToInlineStyles/pull/58)

# Changelog since 1.3.0

* Use Xpath instead of regex, thx to [stof](https://github.com/stof),
    for more information see the [Pull Request](https://github.com/tijsverkoyen/CssToInlineStyles/pull/52)

# Changelog since 1.2.1

* sortOnSpecifity is now working correctly, thx to [lifo101](https://github.com/lifo101),
    for more information see the [Pull Request](https://github.com/tijsverkoyen/CssToInlineStyles/pull/37)

# Changelog since 1.2.0

* introduced a flag to remove media queries before inlining. thx to [Stof](https://github.com/stof).

# Changelog since 1.1.0

* require php 5.3

# Changelog since 1.0.6

* made the class compliant with PSR2.

# Changelog since 1.0.5

* made the class available through composer

# Changelog since 1.0.4

* beter handling of XHTML output, thx to Michele Locati.
* preserve original styles.

# Changelog since 1.0.3

* fixed some code-styling issues
* added support for multiple values

# Changelog since 1.0.2

* .class are matched from now on.
* fixed issue with #id
* new beta-feature: added a way to output valid XHTML (thx to Matt Hornsby)
* added setEncoding() to indicate the encoding

# Changelog since 1.0.1

* fixed some stuff on specifity

# Changelog since 1.0.0

* rewrote the buildXPathQuery-method
* fixed some stuff on specifity
* added a way to use inline style-blocks
