#!/usr/bin/env python

import Image
import os, sys

def resizeImage(dir, filename, output_dir=""):

	try :
		im = Image.open(dir + filename)
		#im.thumbnail(size, Image.ANTIALIAS)
		im.resize((200, 200), Image.ANTIALIAS).save(output_dir + filename, "png")
		#im.save(output_dir + filename, "png")
	except IOError:
		print "cannot reduce image for ", filename


if __name__=="__main__":
	print "Starting resizing...."

	dir = "/home/aso/Scaricati/Telegram Desktop/3/surprise/";
	output_dir = "/home/aso/Scaricati/Telegram Desktop/3/surprise_new/"
	
	if not os.path.exists(os.path.join(output_dir)):
		os.mkdir(output_dir)

	#print os.listdir(dir)
	for filename in os.listdir(dir):
		resizeImage(dir, filename, output_dir)
	
	print "Resizing completed"