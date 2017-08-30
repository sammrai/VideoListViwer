import sys,os
import signal
f=open("jobpid","r")
pid= int(f.read())

try:
	os.kill(pid, signal.SIGKILL)
	os.remove("jobpid")
	print "0"
except:
	print "1"


