parrot = "Norwegian Blue"

print(parrot)
print(parrot[3])

winners = "we win"
for i in range(0, 6):
    print(winners[i])

print()
print(parrot[-1])
print()

for j in range(-6, 0):
    print(winners[j])

print()
print("#################################################################")
print("SLICING")
print("#################################################################")

intaka = "Norwegian Blue"
print(intaka[0:6])
print(intaka[3:5])
print(intaka[0:9])
print(intaka[:9])
print(intaka[10:14])
print(intaka[10:])
print(intaka[:])
print()
print("#################################################################")
print("NEGATIVE SLICING")
print("#################################################################")
print()
print(intaka[-4:-2])
print(intaka[-4:12])
print(intaka[:-8])
print()
print("#################################################################")
print("Using Step in SLICING")
print("#################################################################")
print()

vukuthu = "Norwegian Blue"
print(vukuthu[0:6:2])
print(vukuthu[0:6:3])

number = "1;1321:2232!1123*2112:3221/31"
separators = number[1::5]         #starts at 1 to end and every 5
print(separators)

values = "".join(char if char not in separators else " " for char in number).split()
print(values)
print([int(val) for val in values])

