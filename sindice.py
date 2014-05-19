import os, sys, math, requests, json, zipfile, time

q = sys.argv[1] if len(sys.argv) > 1 else ''
s = '*'
p = '<subClassOf>'
o = '\''+q+'\''
o_br = '<'+q+'>'
rf = 'json'
fq = 'format:RDF'
subclass_query= s+' '+p+' '+o
superclass_query= o_br+' '+p+' '+s
labels_query='* <label>'+o
url = 'http://api.sindice.com/v3/search'
parameters = {'field':'domain', 'format':rf, 'fq':fq, 'page':sys.argv[3]}

if len(sys.argv) > 4:
	if sys.argv[4] != 'labels':
		parameters['fq'] = parameters['fq']+' domain:'+sys.argv[4]
	else:
		parameters['nq'] = labels_query
if len(sys.argv) > 5:
	parameters['nq'] = labels_query
if sys.argv[2] == 'sub':
	parameters['nq'] = subclass_query
elif sys.argv[2] == 'super':
	parameters['nq'] = superclass_query
elif sys.argv[2] == 'key':
	if not parameters.has_key('nq'):
		parameters['q'] = q	

if sys.argv[3] != '-1':
	results = requests.get(url, params=parameters)
	r = results.text
	print(r.encode('utf8'))
else:
        parameters['page'] = '1'
	results = requests.get(url, params=parameters).json()
	numres = int(results['totalResults'])
	pages = (min(100, int(math.ceil(numres/10.0))))

	if len(sys.argv) > 4 and sys.argv[4] != 'labels':

                path = 'results.zip'
                if os.path.exists(path): os.remove(path)
                zip = zipfile.ZipFile(path, 'a')
		for i in range(1, pages+1):
			parameters['page'] = str(i)
			try: results = requests.get(url, params=parameters).json()
                        except ValueError, e: continue
			t = 0.2
			while results.has_key('error') and '[406]' in results['error']:
				time.sleep(t)
				t = t + 0.1                        	
				try: results = requests.get(url, params=parameters).json()
                        	except ValueError, e: break
			try:
				for r in results['entries']:
        	                        link = 'http://api.sindice.com/v2/live?url='+r['link']
        	                        print(link)
        	                        try: tmp = requests.get(link).json()
        	                        except ValueError, e: continue
					t = 0.2
        	                        while tmp.has_key('error') and '[406]' in tmp['error']:				    
        	                            time.sleep(t)
					    t = t + 0.1
        	                            try: tmp = requests.get(link).json()
        	                            except ValueError, e: break
					try:
        	                        	ttuples = ''
        	                        	for b in tmp['extractorResults']['metadata']['explicit']['bindings']:
        	                        	  	ts = b['s']['value']
        	                         		tp = b['p']['value']
        	                            		to = b['o']['value']
        	                            		tt = ts+"\t"+tp+"\t"+to+"\n"
        	                            		ttuples = ttuples+tt
        	                        	zip.writestr(r['link'][7:].replace('/', '_')+'.txt', ttuples.encode('utf8'))
					except KeyError, e: continue
			except KeyError, e: continue
                zip.close()

        else:
            domain_map = {}
            for i in range(1, pages+1):
                    parameters['page'] = str(i)
                    results = requests.get(url, params=parameters).json()
                    for r in results['entries']:
                            if domain_map.has_key(r['domain']):
                                domain_map[r['domain']].append(r['link'])
                            else:
                                domain_map[r['domain']] = [r['link']]

            print(json.dumps(domain_map))
	
