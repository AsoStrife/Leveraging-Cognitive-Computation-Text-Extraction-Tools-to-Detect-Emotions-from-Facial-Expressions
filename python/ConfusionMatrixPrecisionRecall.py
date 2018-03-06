from __future__ import division
import pandas as pd
import numpy as np
import pickle
from pyspark import SparkContext, SparkConf
from pyspark.sql import SQLContext, functions as fn
from sklearn.metrics import confusion_matrix

def getFirstColumn(line):
	parts = line.split(',')
	return parts[0]

def getSecondColumn(line):
	parts = line.split(',')
	return parts[1]

# Initialization
conf= SparkConf()
conf.setAppName("ConfusionMatrixPrecisionRecall")
conf.set('spark.driver.memory','6g')
conf.set('spark.executor.memory','6g')
conf.set('spark.cores.max',156)

sc = SparkContext(conf= conf) # SparkContext
sqlContext = SQLContext(sc) # SqlContext

data = sc.textFile('data/ordered_dataset.txt') # Load dataset

y_true = data.map(getFirstColumn).collect() # Split from line the class
y_pred = data.map(getSecondColumn).collect() # Split from line the tags

confusion_matrix = confusion_matrix(y_true, y_pred)
print("Confusion matrix:\n%s" % confusion_matrix)

# The True Positives are simply the diagonal elements
TP = np.diag(confusion_matrix)
print("\nTP:\n%s" % TP)
print("Media TP:\n%s" % np.mean(TP))

# The False Positives are the sum of the respective column, minus the diagonal element (i.e. the TP element
FP = np.sum(confusion_matrix, axis=0) - TP
print("\nFP:\n%s" % FP)
print("Media FP:\n%s" % np.mean(FP))

# The False Negatives are the sum of the respective row, minus the diagonal (i.e. TP) element:
FN = np.sum(confusion_matrix, axis=1) - TP
print("\nFN:\n%s" % FN)
print("Media FN:\n%s" % np.mean(FN))

num_classes = 7 #static kwnow a priori
TN = []

for i in range(num_classes):
    temp = np.delete(confusion_matrix, i, 0)    # delete ith row
    temp = np.delete(temp, i, 1)  # delete ith column
    TN.append(sum(sum(temp)))
print("\nTN:\n%s" % TN)
print("Media TN:\n%s" % np.mean(TN))

#print TP #FP #FN #TN #checker

'''
# Sanity check: for each class, the sum of TP, FP, FN, and TN must be equal to the size of our test set
l = 644
for i in range(num_classes):
    print(TP[i] + FP[i] + FN[i] + TN[i] == l)
 '''

precision = TP/(TP+FP)
recall = TP/(TP+FN)

print("\nPrecision:\n%s" % precision)
print("Media precision:\n%s" % np.mean(precision))

print("\nRecall:\n%s" % recall)
print("Media recall:\n%s" % np.mean(recall))

FMeasure =  (2 * precision * recall) / (precision + recall)
print("\nF-Measure:\n%s" % FMeasure)
print("Media F-Measure:\n%s" % np.mean(FMeasure))
