import sys, math, requests, json

q = sys.argv[1] if len(sys.argv) > 1 else ''
s = '*'
p = '<subClassOf>'
o = '\''+q+'\''
o_br = '<'+q+'>'
rf = 'json'
fq = 'format:RDF'
subclass_query= s+' '+p+' '+o
superclass_query= o_br+' '+p+' '+s
url = 'http://api.sindice.com/v3/search'
parameters = {'field':'domain', 'format':rf, 'fq':fq, 'page':sys.argv[3]}

if len(sys.argv) > 4:
	parameters['fq'] = parameters['fq']+' domain:'+sys.argv[4]
if sys.argv[2] == 'sub':
	parameters['nq'] = subclass_query
elif sys.argv[2] == 'super':
	parameters['nq'] = superclass_query
elif sys.argv[2] == 'key':
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
	

