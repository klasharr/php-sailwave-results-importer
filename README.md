PHP Sailwave Results Importer
====================================

Extract race results from sailwave files and insert into a DB. Useful to produce a season's stats. It's a bit manual but for the number of times this is used which is once a year, I'll not do much more on it.

Steps.

1. Copy data from sailwave HTML files excel sheets as per [sample format](https://github.com/klasharr/php-sailwave-results-importer/blob/master/example_results/example_format.csv).

2. Notes

Make sure that:

- there are no extra columns or lines
- double barrelled names are hypenated
- check that the header is in this format: 

Rank,Fleet,Class,Sail No,Helm,Crew,PY,16/09/2018,30/09/2018,07/10/2018,14/10/2018,Total,Nett

Note date format

- Watch out for cup races that were also scored as series races, this will duplicate results
- check how you are counting sails, is it crew & helm, or individual boats.
- backup the existing table first
