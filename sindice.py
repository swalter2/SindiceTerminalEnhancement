import sys, math, requests, json

q = sys.argv[1] if len(sys.argv) > 1 else ''
s = '*'
p = '<subClassOf>'
o = '\''+q+'\''
o_br = '<'+q+'>'
rf = 'json'
subclass_query= s+' '+p+' '+o
superclass_query= o_br+' '+p+' '+s

if sys.argv[2] == 'sub':
	parameters = {'nq':subclass_query, 'format':rf, 'page':sys.argv[3]}
elif sys.argv[2] == 'super':
	parameters = {'nq':superclass_query, 'format':rf, 'page':sys.argv[3]}
elif sys.argv[2] == 'key':
	parameters = {'q':q, 'format':rf, 'page':sys.argv[3]}

results = requests.get('http://api.sindice.com/v3/search', params=parameters)
r = results.text
print(r.encode('utf8'))
#results_json = results.json()
#for r in results_json['entries']:
#		print(r['link'])

#numres = int(results_json['totalResults'])
#pages = (min(100, int(math.ceil(numres/10.0))))
#pages = 2

#for i in range(2, pages+1):
#	parameters['page'] = str(i)
#	results = requests.get('http://api.sindice.com/v3/search', params=parameters)
#	results_json = results.json()
#	for r in results_json['entries']:
#		print(r['link'])
