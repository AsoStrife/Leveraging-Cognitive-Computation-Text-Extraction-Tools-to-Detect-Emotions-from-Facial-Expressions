#!flask/bin/python

# START SERVICE
# cd progetto
# source venv/bin/activate --serve-in-foreground
# nohup python service.py &  	
# https://stackoverflow.com/questions/2975624/how-to-run-a-python-script-in-the-background-even-after-i-logout-ssh

# KILL SERVICE
# ps auxwww|grep -i 'python'
# kill PID
# deactivate 
# https://stackoverflow.com/questions/26840123/unable-to-kill-nohup-process
import pickle
import subprocess
from flask import Flask, request
from flask_restful import Resource, Api
from json import dumps
from flask_jsonpify import jsonify
from flask_cors import CORS #pip install -U flask-cors
import json

app = Flask(__name__)
CORS(app)
api = Api(app)

class Classifier(Resource):
	def post(self):
		imageID = request.form.get('imageID')
		tags = request.form.get('tags')
		
		if (imageID is None) or (tags is None):
			data = {}
			data['id'] = "0"
			data['class'] = "undefined"
			json_data = json.dumps(data)
			return json_data

		p = subprocess.Popen(["spark-submit", "NaiveBayesClassifier.py", tags], stdout=subprocess.PIPE)
		imageClass =  p.communicate()
		imageClass = imageClass[0].rstrip()

		data = {}
		data['id'] = imageID
		data['class'] = imageClass
		json_data = json.dumps(data)
		return json_data


class Home(Resource):
	def get(self):
		message = str("Service classifier by Andrea Corriga & Francesca Malloci")
		return jsonify(message)

api.add_resource(Classifier, '/classifier') 
api.add_resource(Home, '/home')

if __name__ == '__main__':
	app.run(port=5002)
