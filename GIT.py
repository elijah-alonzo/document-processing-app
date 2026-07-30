import subprocess
import sys
from datetime import datetime


def run(command):
    print(f"\n> {command}")
    result = subprocess.run(command, shell=True)
    if result.returncode != 0:
        print("\nCommand failed.")
        sys.exit(result.returncode)


def main():
    args = sys.argv[1:]
    push = False

    if "-p" in args:
        push = True
        args.remove("-p")

    if "--push" in args:
        push = True
        args.remove("--push")

    if args:
        message = " ".join(args)
    else:
        message = input("Commit message: ").strip()

    if not message:
        message = f"Update {datetime.now():%Y-%m-%d %H:%M:%S}"

    run("git add .")
    run(f'git commit -m "{message}"')

    if push:
        run("git push")

    print("\n✓ Commit complete!")

    if push:
        print("✓ Changes pushed to remote.")


if __name__ == "__main__":
    main()