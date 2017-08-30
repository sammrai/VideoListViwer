import time,os
print os.getpid()

for i in range(6):
	# print i
	f=open("test_%03d"%i,"w")
	f.close()
	time.sleep(1)

