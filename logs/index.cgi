#!/bin/sh
# get today's date
last | sed -r 's/(^.*still logged in)/<div class="color">\1<\/div>/g'  > tmp.txt

OUTPUT="$(cat tmp.txt)"

echo "Content-type: text/html"
echo ""
echo "<html><head><title>PPTP Connection Log</title><style>.color {display:inline; color:#e22;}</style></head><body>"
echo "<pre>$OUTPUT</pre>"
echo "</body></html>"
