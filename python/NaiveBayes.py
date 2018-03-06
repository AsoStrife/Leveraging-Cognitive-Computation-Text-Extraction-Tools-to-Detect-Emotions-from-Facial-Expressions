import pandas as pd
import numpy as np
import pickle
from pyspark import SparkContext, SparkConf
from pyspark.sql import SQLContext, functions as fn
from pyspark.mllib.classification import NaiveBayes, NaiveBayesModel
from pyspark.mllib.linalg import Vectors
from pyspark.mllib.regression import LabeledPoint
from pyspark.ml.feature import CountVectorizer, CountVectorizerModel #sudo apt install python3-sklearn 
from pyspark.mllib.linalg import SparseVector, DenseVector



# Class values string to float
classValues =  {'anger': 	'0',
				'contempt':	'1',
				'disgust': 	'2',
				'fear': 	'3',
				'happiness':'4',
				'neutral':	'5',
				'sadness': 	'6',
				'surprise': '7'}
# Get the class
def getClass(line):
	parts = line.split(',')
	return float(classValues[parts[0]])

# Get a list of tags
def getTags(line):
	parts = line.split(',')
	return parts[1].split(" ")

# Generate a LabeledPoint(class, features)
def mapper(line):
	return LabeledPoint(line.classes, line.vectors)

# Initialization
conf= SparkConf()
conf.setAppName("NaiveBaye")
conf.set('spark.driver.memory','6g')
conf.set('spark.executor.memory','6g')
conf.set('spark.cores.max',156)

sc = SparkContext(conf= conf) # SparkContext
sqlContext = SQLContext(sc) # SqlContext

data = sc.textFile('data/dataset.txt') # Load dataset

classes = data.map(getClass).collect() # Split from line the class
tags = data.map(getTags).collect() # Split from line the tags

d = {
    'tags' : tags,
    'classes' : classes
}

df = sqlContext.createDataFrame(pd.DataFrame(data=d))

cv = CountVectorizer(inputCol="tags", outputCol="vectors")
fit = cv.fit(df)

#fit.transform(df).show(truncate=False) # mostro la tabella del vettori

fitTransform = fit.transform(df) # 	Learn a vocabulary dictionary of all tokens in the raw documents.

fitTransform = fitTransform.map(mapper) #mappo il dataframe convertendolo in labeledPoint per il naive bayes
vocabulary =  map(str, fit.vocabulary)


#print len(vocabulary)
#print vocabulary

# Store the vocabulary model
with open("model/vocabulary", 'wb') as f:
	pickle.dump(vocabulary, f)

#with open("data/vocabulary", 'rb') as f:
 #   my_list = pickle.load(f)

#print my_list

# Split data aproximately into training (90%) and test (10%)
training, test = fitTransform.randomSplit([0.9, 0.1])

#print training
# Train a naive Bayes model.
model = NaiveBayes.train(training, 1.0)


# Make prediction and test accuracy.
predictionAndLabel = test.map(lambda p: (model.predict(p.features), p.label))
accuracy = 1.0 * predictionAndLabel.filter(lambda (x, v): x == v).count() / test.count()

# Save and load model
#model.save(sc, "model")

print("Accuracy: " + str(accuracy))