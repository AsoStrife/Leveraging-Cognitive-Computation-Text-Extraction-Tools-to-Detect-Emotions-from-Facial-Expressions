import pickle
import numpy
from pyspark import SparkContext, SparkConf
from pyspark.sql import SQLContext, functions as fn
from pyspark.mllib.classification import NaiveBayes, NaiveBayesModel
from pyspark.mllib.linalg import SparseVector, DenseVector
import sys

classValues =  {0.0: 'anger',
				1.0: 'contempt',
				2.0: 'disgust',
				3.0: 'fear',
				4.0: 'happiness',
				5.0: 'neutral',
				6.0: 'sadness',
				7.0: 'surprise'}

# Generate the bow with exist vocabulary
def bow(tags):

	with open("model/vocabulary", 'rb') as f:
		vocabulary = pickle.load(f)
	
	terms = tags.split()
	
	# filter words that not exist in the vocabulary
	terms =  [x for x in list(set(terms)) if x in list(set(vocabulary))] 

	indices = list(map(lambda x: vocabulary.index(x), list(set(terms))))
	indices.sort()
	occurrences = list(map(lambda x: float(terms.count(vocabulary[x])), indices))

	return [len(vocabulary), indices, occurrences]


conf= SparkConf()
conf.setAppName("NaiveBaye")
conf.set('spark.driver.memory','6g')
conf.set('spark.executor.memory','6g')
conf.set('spark.cores.max',156)

#load tags passed as parameter
tags = sys.argv[1]
bow =  bow(tags) #bag of words of that tags

sc = SparkContext(conf= conf) # SparkContext

model = NaiveBayesModel.load(sc, "model")

result = model.predict( SparseVector( bow[0], bow[1], bow[2] ) ) 

print str(classValues[result])