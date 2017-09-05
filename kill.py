import sys
import test
import argparse

def getparse():
	# command line arguments
	parser = argparse.ArgumentParser(description='process manager')
	parser.add_argument(dest='file_in' , metavar='FLOAT')
	parser.add_argument('-p', dest='pid', required=False)
	parser.add_argument('-r', dest='reflesh', action='store_true', required=False, default=False)
	parser.add_argument('-a', dest='all', action='store_true', required=False, default=False)
	return parser.parse_args()

args = getparse()
jm=test.jobmng(args.file_in)

if args.pid:
	print "remove"
	jm.kill_process(args.pid)
if args.reflesh:
	print "reflesh"
	jm.reflesh()
if args.all:
	print "all"
	jm.killall()
