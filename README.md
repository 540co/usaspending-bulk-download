## usaspending.gov bulk download

###What does the script do?
Simple script to automate the download of files from [usaspending.gov](http://usaspending.gov)

###Dependencies?
Requires PHP CLI to be loaded - tested with version `PHP 5.4.29`

###How do I use?
From a command line run the following:

`php import.php [RECORD_TYPE] [FROM_YEAR] [TO_YEAR] [DOWNLOAD_DIR] [FILE_FORMAT]`

|ARGUMENTS  		| DESCRIPTION  |
|-----------------|----------------|
|`[RECORD_TYPE]`   	|   The type of records you want to download from USASPENDING.GOV<br ><br >`primeawardcontracts`<br >`subawardcontracts`<br > `primeawardgrants`<br >`subawardgrants`<br >`primeawardloans`<br >`primeawarddirectpayments`<br >`primeawardinsurance`<br >`primeawardother`  |
|`[FROM_YEAR]`    	|   Year to start with (Example: 2010)   |
|`[TO_YEAR]`  		|   Year to end with (Example: 2014)   |
|`[DOWNLOAD_DIR]`	|   Where to download files to (some files are big, so make sure there is lots of disk space available)  |
|`[FILE_FORMAT]`  		|   File format<br ><br >`CSV`<br >`TSV`<br >`XML`<br >`Atom` |

###How does it work?
It submits the query information provided to `http://usaspending.gov/data` form and parses results to determine the appropriate download link.  It then initiates the download.

>To see the query parameters submitted to the form, refer to the `usaspending.json` file.

###Warnings
The script does not include error checking on valid values, exceptions, etc.  It is designed to run at your own risk.  

Also, be a good citizen and be careful not to run multiple copies against the usaspending.gov server to minimize load.  