#!/usr/bin/env python3
"""Extract brokers rows from a legacy phpMyAdmin SQL dump into JSON."""

from __future__ import annotations

import argparse
import json
import re
import sys
from pathlib import Path


def split_sql_values(values_sql: str) -> list[str]:
    """Split a MySQL VALUES (...) tuple into raw field strings (unquoted later)."""
    fields: list[str] = []
    buf: list[str] = []
    in_string = False
    escape = False
    i = 0
    s = values_sql.strip()
    if s.startswith("(") and s.endswith(")"):
        s = s[1:-1]

    while i < len(s):
        ch = s[i]
        if in_string:
            if escape:
                buf.append(ch)
                escape = False
            elif ch == "\\":
                buf.append(ch)
                escape = True
            elif ch == "'":
                # Lookahead for escaped '' inside string
                if i + 1 < len(s) and s[i + 1] == "'":
                    buf.append("''")
                    i += 2
                    continue
                in_string = False
                buf.append(ch)
            else:
                buf.append(ch)
        else:
            if ch == "'":
                in_string = True
                buf.append(ch)
            elif ch == ",":
                fields.append("".join(buf).strip())
                buf = []
            else:
                buf.append(ch)
        i += 1

    if buf or s.endswith(","):
        fields.append("".join(buf).strip())

    return fields


def unquote_sql(value: str):
    value = value.strip()
    if value.upper() == "NULL":
        return None
    if value.startswith("'") and value.endswith("'"):
        inner = value[1:-1]
        inner = inner.replace("\\'", "'").replace("''", "'")
        inner = inner.replace('\\"', '"').replace("\\n", "\n").replace("\\r", "\r")
        inner = inner.replace("\\t", "\t").replace("\\\\", "\\")
        return inner
    # numeric / bare
    if re.fullmatch(r"-?\d+", value):
        return int(value)
    if re.fullmatch(r"-?\d+\.\d+", value):
        return float(value)
    return value




def read_values_blob(text: str, start: int) -> str:
    """Read INSERT VALUES payload until the terminating semicolon (string-aware)."""
    in_string = False
    escape = False
    depth = 0
    i = start
    while i < len(text):
        ch = text[i]
        if in_string:
            if escape:
                escape = False
            elif ch == "\\":
                escape = True
            elif ch == "'":
                if i + 1 < len(text) and text[i + 1] == "'":
                    i += 1
                else:
                    in_string = False
            i += 1
            continue
        if ch == "'":
            in_string = True
        elif ch == "(":
            depth += 1
        elif ch == ")":
            depth = max(0, depth - 1)
        elif ch == ";" and depth == 0:
            return text[start:i]
        i += 1
    return text[start:]


def extract(sql_path: Path) -> list[dict]:
    text = sql_path.read_text(encoding="utf-8", errors="replace")

    create = re.search(
        r"CREATE TABLE\s+`?brokers`?\s*\((.*?)\)\s*(?:ENGINE|;)",
        text,
        re.IGNORECASE | re.DOTALL,
    )
    if not create:
        raise SystemExit("CREATE TABLE brokers not found")

    columns = re.findall(r"`([^`]+)`", create.group(1))
    # Filter out KEY / PRIMARY definitions that also use backticks
    # Prefer columns before PRIMARY KEY line
    primary_idx = create.group(1).upper().find("PRIMARY KEY")
    col_block = create.group(1) if primary_idx < 0 else create.group(1)[:primary_idx]
    columns = re.findall(r"`([^`]+)`", col_block)

    rows: list[dict] = []
    insert_re = re.compile(
        r"INSERT INTO\s+`?brokers`?\s*\((.*?)\)\s*VALUES\s*",
        re.IGNORECASE | re.DOTALL,
    )
    for match in insert_re.finditer(text):
        insert_cols = [c.strip().strip("`") for c in match.group(1).split(",")]
        values_blob = read_values_blob(text, match.end()).strip()

        # Split top-level value tuples: ),(
        tuples: list[str] = []
        depth = 0
        start = None
        in_string = False
        escape = False
        for i, ch in enumerate(values_blob):
            if in_string:
                if escape:
                    escape = False
                elif ch == "\\":
                    escape = True
                elif ch == "'":
                    if i + 1 < len(values_blob) and values_blob[i + 1] == "'":
                        # skip doubled quote; loop will still see next
                        pass
                    else:
                        in_string = False
                continue
            if ch == "'":
                in_string = True
                continue
            if ch == "(":
                if depth == 0:
                    start = i
                depth += 1
            elif ch == ")":
                depth -= 1
                if depth == 0 and start is not None:
                    tuples.append(values_blob[start : i + 1])
                    start = None

        for tup in tuples:
            raw_fields = split_sql_values(tup)
            if len(raw_fields) != len(insert_cols):
                print(
                    f"WARN: col/field mismatch {len(insert_cols)} vs {len(raw_fields)}",
                    file=sys.stderr,
                )
                continue
            row = {
                col: unquote_sql(raw)
                for col, raw in zip(insert_cols, raw_fields)
            }
            rows.append(row)

    return rows


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Extract brokers rows from a legacy phpMyAdmin SQL dump into JSON."
    )
    parser.add_argument(
        "source",
        nargs="?",
        default=r"c:\Users\Sakib\Downloads\brokers (1).sql",
        help="Path to the legacy SQL dump file.",
    )
    parser.add_argument(
        "-o",
        "--output",
        default=r"F:\vscode\storage\app\imports\legacy_brokers.json",
        help="Path for the generated JSON file.",
    )
    return parser.parse_args()


def main() -> None:
    args = parse_args()
    src = Path(args.source)
    out = Path(args.output)
    if not src.exists():
        raise SystemExit(f"Source SQL file not found: {src}")
    out.parent.mkdir(parents=True, exist_ok=True)
    rows = extract(src)
    out.write_text(json.dumps(rows, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"Wrote {len(rows)} brokers -> {out}")
    if rows:
        print("First:", rows[0].get("name"), rows[0].get("slug"))


if __name__ == "__main__":
    main()
