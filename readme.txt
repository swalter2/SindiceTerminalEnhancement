SindiceTerminalEnhancement
==========================

Terminal Enhancement using Sindice API


Requirements
============
- The python library "requests" needs to be installed
- The website folder needs to be writable


How to use the search
=====================

1. Enter a keyword into the search field
2. Choose an option: 
	- "resources containing search term" returns resources containing the keyword in the URI or the document itself
            - Check "only include resources containing keyword in label" to get only resources having a label predicate set to the keyword
	- "subclasses of those resources" returns resources containing a triple conforming to the pattern "* <subClassOf> keyword"
	- "subclasses of those resources" returns resources containing a triple conforming to the pattern "keyword <subClassOf> *"
3. Press search
4. Choose a domain to filter the results
	a) Browse the results page-wise by clicking the domain name
	b) Click "download" to get the results as a zip archive
	
The results are ordered by relevance. The maximum number of results is 1000.
	
