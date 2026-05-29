import re
import glob

# Files to process
files = glob.glob('resources/views/**/*.blade.php', recursive=True)

pattern = re.compile(r"document\.querySelector\('\[x-data=\\'confirmModal\\'\]'\)\.__x\.\$data\.show\((.*?)\)")

def replacer(match):
    # match.group(1) will be: 'Delete Budget', 'This budget will be permanently removed.', this.closest('form')
    args = match.group(1).split("', '")
    if len(args) >= 2:
        title = args[0].strip("'")
        # Split remaining by "', " to get the message
        msg_parts = args[1].split("', ")
        if len(msg_parts) >= 2:
            msg = msg_parts[0]
            form = msg_parts[1]
            return f"window.dispatchEvent(new CustomEvent('open-confirm', {{ detail: {{ title: '{title}', message: '{msg}', form: {form} }} }}))"
    
    # Fallback to simple replacement if parsing fails
    # Let's write a simpler regex for the specific format we have
    return match.group(0)

# Actually, the arguments are very predictable: 'Title', 'Message', this.closest('form')
pattern2 = re.compile(r"document\.querySelector\('\[x-data=\\'confirmModal\\'\]'\)\.__x\.\$data\.show\('([^']*)',\s*'([^']*)',\s*(.*?)\)")

def replacer2(match):
    title = match.group(1)
    msg = match.group(2)
    form = match.group(3)
    return f"window.dispatchEvent(new CustomEvent('open-confirm', {{ detail: {{ title: '{title}', message: '{msg}', form: {form} }} }}))"

for f in files:
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
    
    new_content, count = pattern2.subn(replacer2, content)
    if count > 0:
        with open(f, 'w', encoding='utf-8') as file:
            file.write(new_content)
        print(f"Fixed {f} ({count} replacements)")
