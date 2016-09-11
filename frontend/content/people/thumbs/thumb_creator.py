import os, sys
import Image

size = 45, 60

for infile in os.listdir(sys.argv[1]):
	try:
		im = Image.open(sys.argv[1] + infile)
		c_size = im.size
		n_height = c_size[1] / (c_size[0]/45)
		i = im.resize((45, n_height), Image.ANTIALIAS)
		i.save("pythumbs/" + os.path.basename(infile), "JPEG")
	except IOError:
		print "cannot create thumbnail for '%s'" % infile
