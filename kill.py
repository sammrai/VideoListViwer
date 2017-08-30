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

if args.pid:
	print "remove"
	test.kill_process(args.file_in,args.pid)
if args.reflesh:
	print "reflesh"
	test.reflesh(args.file_in)
if args.all:
	print "all"
	test.killall(args.file_in)
