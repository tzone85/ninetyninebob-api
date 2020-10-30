a = 12
b = 3

print(a + b)
print(a - b)
print(a * b)
print(a / b)    #4.0
print(a // b)   #integer devision, rounded down towards negative infinity
print(a % b)
print()

for i in range(1, a // b):
    print(i)

print(a + b / 3 - 4 * 12)
print(((a + b) / 3) * 12)

c = a + b
d = c/3
e = d - 4
print(e*12)