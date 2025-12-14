import re

def convert_sql(input_file, output_file):
    with open(input_file, 'r', encoding='utf-8') as f:
        content = f.read()

    # Remove SQL Server specific headers and footers
    content = re.sub(r'USE \[.*?\]\s*GO', '', content, flags=re.IGNORECASE)
    content = re.sub(r'SET .*? GO', '', content, flags=re.IGNORECASE | re.DOTALL)
    content = re.sub(r'SET .*? ON', '', content, flags=re.IGNORECASE)
    content = re.sub(r'SET .*? OFF', '', content, flags=re.IGNORECASE)
    content = re.sub(r'ALTER DATABASE .*? GO', '', content, flags=re.IGNORECASE | re.DOTALL)
    content = re.sub(r'IF \(1 = FULLTEXTSERVICEPROPERTY.*?\)\s*begin\s*EXEC.*?end\s*GO', '', content, flags=re.IGNORECASE | re.DOTALL)
    
    # Remove comments
    content = re.sub(r'/\*.*?\*/', '', content, flags=re.DOTALL)

    # Basic replacements
    content = content.replace('[', '"').replace(']', '"')
    content = re.sub(r'\bGO\b', ';', content) # Only replace GO as a whole word
    content = content.replace('ON "PRIMARY"', '')
    content = content.replace('TEXTIMAGE_ON "PRIMARY"', '')
    
    # Remove WITH (...) clauses for keys
    content = re.sub(r'WITH \(.*?\)', '', content, flags=re.DOTALL)

    # Data type mappings
    content = re.sub(r'nvarchar\(\d+\)', 'varchar', content, flags=re.IGNORECASE)
    content = re.sub(r'nvarchar', 'varchar', content, flags=re.IGNORECASE)
    content = re.sub(r'smalldatetime', 'timestamp', content, flags=re.IGNORECASE) # Match smalldatetime before datetime if needed, or just map both
    content = re.sub(r'datetime', 'timestamp', content, flags=re.IGNORECASE)
    content = re.sub(r'bit', 'boolean', content, flags=re.IGNORECASE)
    content = re.sub(r'decimal', 'numeric', content, flags=re.IGNORECASE)
    content = re.sub(r'float', 'double precision', content, flags=re.IGNORECASE)
    content = re.sub(r'\bint\b', 'integer', content, flags=re.IGNORECASE) # Only replace int as a whole word
    
    # Schema mapping
    content = content.replace('"dbo".', '')
    content = content.replace('"antony".', '') # Assuming antony schema should also be public or removed

    # Clean up empty lines and multiple semicolons
    lines = []
    for line in content.split('\n'):
        line = line.strip()
        if line and line != ';':
            lines.append(line)
    
    final_content = '\n'.join(lines)
    
    # Fix CREATE TABLE syntax (remove trailing comma before closing parenthesis)
    # This is a simple heuristic, might need more robust parsing for complex cases
    # But for this dump it might be enough.
    # Actually, SQL Server dumps usually have commas correctly placed, but let's check.
    
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(final_content)

if __name__ == "__main__":
    convert_sql('script_utf8.sql', 'script_pg.sql')
