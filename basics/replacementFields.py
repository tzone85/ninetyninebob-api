age = 24
print("My age is " + str(age) + " years")
print()

#using python 3.6
print("My age is {0} years old.".format(age))

#example
print("There are {0} days in {1}, {2}, {3}, {4}, {5}, {6}, {7}"
      .format(31, "Jan", "Mar", "May", "Jul", "Aug", "Oct", "Dec"))

# it's the replacement field that dictates the order or the formating
print("Jan: {2}, Feb: {0}, March: {2}, Apr: {1}".format(28, 30, 31))
print()
print("""Jan: {2}
Feb: {0}
March: {2}
Apr: {1}
""".format(28, 30, 31))