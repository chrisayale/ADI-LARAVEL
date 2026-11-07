with open('resources/views/admin/loans/create.blade.php', 'r') as f:
    lines = f.readlines()

start = None
for i, line in enumerate(lines):
    if 'document.addEventListener' in line and 'DOMContentLoaded' in line:
        start = i
        break

if start is not None:
    print('DOMContentLoaded starts at line', start+1)
    brace_count = 0
    paren_count = 0
    bracket_count = 0
    in_string = False
    string_char = None

    for i in range(start, len(lines)):
        line = lines[i]
        for j, ch in enumerate(line):
            if ch in ('"', "'", '`') and (j == 0 or line[j-1] != '\\'):
                if not in_string:
                    in_string = True
                    string_char = ch
                elif ch == string_char:
                    in_string = False
                    string_char = None
                continue
            if in_string:
                continue
            if ch == '{':
                brace_count += 1
            elif ch == '}':
                brace_count -= 1
                if brace_count < 0:
                    print('NEGATIVE BRACE COUNT AT LINE', i+1)
            elif ch == '(':
                paren_count += 1
            elif ch == ')':
                paren_count -= 1
                if paren_count < 0:
                    print('NEGATIVE PAREN COUNT AT LINE', i+1)
            elif ch == '[':
                bracket_count += 1
            elif ch == ']':
                bracket_count -= 1
        if brace_count == 0 and i > start:
            print('Braces balanced at line', i+1)
            break
    print('Final counts: braces={}, parens={}, brackets={}'.format(brace_count, paren_count, bracket_count))