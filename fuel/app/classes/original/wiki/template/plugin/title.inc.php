<?php

/**
 * title.inc.php - Change the title of the page
 *
 * Copyright (c) 2009 revulo
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author     revulo
 * @licence    http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version    1.0
 * @link       http://www.revulo.com/PukiWiki/Plugin/Title.html
 */

function plugin_title_convert()
{
    global $vars, $title, $page, $defaultpage;

    $argc = func_num_args();
    $argv = func_get_args();

    /*
     * Do nothing in the following cases:
     * - No argument is passed.
     * - Plugin is used in an included page.
     * - Plugin is called more than once.
     */
    if ($argc === 0 || $title !== htmlspecialchars($vars['page'])) {
        return;
    }

    $title = htmlspecialchars(trim($argv[0]));
    if ($argc > 1) {
        $page = htmlspecialchars(trim($argv[1]));
    } else {
        $page = $title;
    }
}

?>
